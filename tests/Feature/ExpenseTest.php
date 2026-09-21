<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_an_expense(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/expenses', [
                'title' => 'Groceries',
                'amount' => 45.50,
                'spent_at' => '2026-09-21',
                'description' => 'Weekly shopping',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/expenses');

        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id,
            'title' => 'Groceries',
            'amount' => 45.50,
            'description' => 'Weekly shopping',
        ]);
    }

    public function test_user_can_update_an_expense(): void
    {
        $user = User::factory()->create();
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'title' => 'Old title',
            'amount' => 20.00,
            'spent_at' => '2026-09-01',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/expenses/' . $expense->id, [
                'title' => 'Updated title',
                'amount' => 35.00,
                'spent_at' => '2026-09-15',
                'description' => 'Updated description',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/expenses');

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'title' => 'Updated title',
            'amount' => 35.00,
            'description' => 'Updated description',
        ]);
    }

    public function test_user_can_delete_an_expense(): void
    {
        $user = User::factory()->create();
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete('/expenses/' . $expense->id);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/expenses');

        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id,
        ]);
    }
}
