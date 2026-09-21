<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(): View
    {
        $expenses = Expense::where('user_id', Auth::id())
            ->orderBy('spent_at', 'desc')
            ->get();

        return view('expenses.index', compact('expenses'));
    }

    public function create(): View
    {
        return view('expenses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'spent_at' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['user_id'] = Auth::id();

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    public function edit(Expense $expense): View
    {
        $this->authorizeOwner($expense);

        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $this->authorizeOwner($expense);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'spent_at' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $this->authorizeOwner($expense);

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    protected function authorizeOwner(Expense $expense): void
    {
        abort_unless($expense->user_id === Auth::id(), 403, 'Unauthorized action.');
    }
}
