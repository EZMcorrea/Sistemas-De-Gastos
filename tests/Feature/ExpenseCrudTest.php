<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_read_update_delete_expense()
    {
        // seed categories and payments
        $cat = Category::create(['name' => 'TesteCat']);
        $pay = PaymentMethod::create(['name' => 'TestePay']);

        // Create
        $response = $this->post('/gastos', [
            'date' => now()->format('Y-m-d'),
            'category_id' => $cat->id,
            'description' => 'Teste via feature',
            'value' => 10.50,
            'due_date' => now()->addDays(5)->format('Y-m-d'),
            'status' => 'Pendente',
            'payment_method_id' => $pay->id,
            'notes' => 'nota',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('expenses', ['description' => 'Teste via feature', 'value' => 10.50]);

        $expense = Expense::where('description', 'Teste via feature')->first();
        $this->assertNotNull($expense);

        // Update
        $res2 = $this->put('/gastos/' . $expense->id, [
            'date' => now()->format('Y-m-d'),
            'category_id' => $cat->id,
            'description' => 'Atualizado',
            'value' => 20.00,
            'due_date' => now()->addDays(2)->format('Y-m-d'),
            'status' => 'Pago',
            'payment_method_id' => $pay->id,
            'notes' => 'nota2',
        ]);
        $res2->assertRedirect();
        $this->assertDatabaseHas('expenses', ['description' => 'Atualizado', 'value' => 20.00]);

        // Delete
        $res3 = $this->delete('/gastos/' . $expense->id);
        $res3->assertRedirect();
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }
}
