<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Expenses') }}
            </h2>

            <a href="{{ route('expenses.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Add Expense') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-100 border border-green-400 text-green-700 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($expenses->isEmpty())
                        <p class="text-gray-600 dark:text-gray-300">No expenses recorded yet.</p>
                    @else
                        <div class="table-responsive">
                            <table id="expense-table" class="table table-striped table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expenses as $expense)
                                        <tr class="expense-row">
                                            <td>{{ $expense->title }}</td>
                                            <td>${{ number_format($expense->amount, 2) }}</td>
                                            <td>{{ $expense->spent_at->format('M d, Y') }}</td>
                                            <td>{{ $expense->description ?? '—' }}</td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-primary btn-sm">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Delete this expense?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div id="expense-pagination-info" class="text-muted small"></div>
                                <nav aria-label="Expense pagination">
                                    <ul id="expense-pagination" class="pagination pagination-sm mb-0"></ul>
                                </nav>
                            </div>
                        </div>

                        <script>
                            $(document).ready(function () {
                                const rowsPerPage = 5;
                                const $rows = $('.expense-row');
                                const $pagination = $('#expense-pagination');
                                const $info = $('#expense-pagination-info');
                                let currentPage = 1;
                                const totalPages = Math.max(1, Math.ceil($rows.length / rowsPerPage));

                                function buildPagination() {
                                    $pagination.empty();

                                    const prevItem = $('<li class="page-item"><a class="page-link" href="#" aria-label="Previous">&laquo;</a></li>');
                                    prevItem.toggleClass('disabled', currentPage === 1);
                                    prevItem.on('click', function (e) {
                                        e.preventDefault();
                                        if (currentPage > 1) {
                                            currentPage--;
                                            renderPage();
                                        }
                                    });
                                    $pagination.append(prevItem);

                                    for (let page = 1; page <= totalPages; page++) {
                                        const item = $('<li class="page-item"><a class="page-link" href="#">' + page + '</a></li>');
                                        if (page === currentPage) {
                                            item.addClass('active');
                                        }

                                        item.on('click', function (e) {
                                            e.preventDefault();
                                            currentPage = page;
                                            renderPage();
                                        });

                                        $pagination.append(item);
                                    }

                                    const nextItem = $('<li class="page-item"><a class="page-link" href="#" aria-label="Next">&raquo;</a></li>');
                                    nextItem.toggleClass('disabled', currentPage === totalPages);
                                    nextItem.on('click', function (e) {
                                        e.preventDefault();
                                        if (currentPage < totalPages) {
                                            currentPage++;
                                            renderPage();
                                        }
                                    });
                                    $pagination.append(nextItem);
                                }

                                function renderPage() {
                                    const start = (currentPage - 1) * rowsPerPage;
                                    const end = start + rowsPerPage;

                                    $rows.hide();
                                    $rows.slice(start, end).show();

                                    const startItem = $rows.length ? start + 1 : 0;
                                    const endItem = Math.min(end, $rows.length);
                                    $info.text('Showing ' + startItem + '-' + endItem + ' of ' + $rows.length + ' expenses');

                                    buildPagination();
                                }

                                renderPage();
                            });
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
