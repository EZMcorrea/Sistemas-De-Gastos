<?php

namespace Tests\Feature;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_update_and_delete_payment_method()
    {
        // Create
        $response = $this->post('/pagamentos', ['name' => 'PagamentoTeste']);
        $response->assertRedirect();
        $this->assertDatabaseHas('payment_methods', ['name' => 'PagamentoTeste']);

        $pm = PaymentMethod::where('name', 'PagamentoTeste')->first();
        $this->assertNotNull($pm);

        // Update
        $res2 = $this->put('/pagamentos/' . $pm->id, ['name' => 'PagamentoAtualizado']);
        $res2->assertRedirect();
        $this->assertDatabaseHas('payment_methods', ['name' => 'PagamentoAtualizado']);

        // Delete
        $res3 = $this->delete('/pagamentos/' . $pm->id);
        $res3->assertRedirect();
        $this->assertDatabaseMissing('payment_methods', ['id' => $pm->id]);
    }
}
