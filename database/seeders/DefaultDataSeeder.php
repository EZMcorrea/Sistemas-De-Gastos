<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expense;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class DefaultDataSeeder extends Seeder
{
    public function run(): void
    {
        // Categorias padrão
        $categories = [
            'Água', 'Luz', 'Cartão de crédito', 'Internet', 'Celular',
            'IPTU', 'Placa Solar', 'Consórcio', 'Aluguel', 'Condomínio',
            'Gás', 'Seguro', 'Streaming', 'Supermercado', 'Transporte',
            'Saúde', 'Educação', 'Lazer', 'Pensão Alim.', 'Imposto DAS', 'Outros',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }

        // Formas de pagamento padrão
        $payments = ['PIX', 'Débito', 'Crédito', 'Boleto', 'Dinheiro', 'Transferência'];

        foreach ($payments as $name) {
            PaymentMethod::firstOrCreate(['name' => $name]);
        }

        // Dados iniciais (seed) — mesmos do HTML original
        $pix = PaymentMethod::where('name', 'PIX')->first();

        $seedData = [
            ['date' => '2026-03-30', 'category' => 'Luz',               'desc' => '',            'value' => 299.31,  'due' => '2026-04-15', 'status' => 'Pago',     'pay' => $pix?->id],
            ['date' => '2026-03-30', 'category' => 'IPTU',              'desc' => '',            'value' => 74.63,   'due' => '2026-04-15', 'status' => 'Pago',     'pay' => $pix?->id],
            ['date' => '2026-03-30', 'category' => 'Placa Solar',       'desc' => '',            'value' => 558.46,  'due' => '2026-05-11', 'status' => 'Pendente', 'pay' => null],
            ['date' => '2026-03-30', 'category' => 'Celular',           'desc' => '',            'value' => 77.94,   'due' => '2026-04-12', 'status' => 'Pago',     'pay' => $pix?->id],
            ['date' => '2026-03-30', 'category' => 'Cartão de crédito', 'desc' => 'Mercado Pago','value' => 182.19,  'due' => '2026-04-12', 'status' => 'Pago',     'pay' => $pix?->id],
            ['date' => '2026-03-30', 'category' => 'Cartão de crédito', 'desc' => 'Itau',       'value' => 1071.05, 'due' => '2026-04-13', 'status' => 'Pago',     'pay' => $pix?->id],
            ['date' => '2026-03-30', 'category' => 'Cartão de crédito', 'desc' => 'Hipercard',  'value' => 566.16,  'due' => '2026-04-13', 'status' => 'Pago',     'pay' => $pix?->id],
            ['date' => '2026-03-30', 'category' => 'Água',              'desc' => '',            'value' => 153.48,  'due' => '2026-04-16', 'status' => 'Pago',     'pay' => $pix?->id],
            ['date' => '2026-03-30', 'category' => 'Consórcio',         'desc' => 'Ademicon',   'value' => 674.60,  'due' => '2026-04-15', 'status' => 'Pago',     'pay' => $pix?->id],
            ['date' => '2026-03-30', 'category' => 'Pensão Alim.',      'desc' => 'Joao Vitor', 'value' => 599.77,  'due' => '2026-04-15', 'status' => 'Pago',     'pay' => $pix?->id],
            ['date' => '2026-04-01', 'category' => 'Internet',          'desc' => '',            'value' => 104.90,  'due' => '2026-04-10', 'status' => 'Pago',     'pay' => $pix?->id],
            ['date' => '2026-04-02', 'category' => 'Imposto DAS',       'desc' => '',            'value' => 87.05,   'due' => '2026-04-20', 'status' => 'Pago',     'pay' => $pix?->id],
            ['date' => '2026-04-02', 'category' => 'Cartão de crédito', 'desc' => 'Picpay',     'value' => 1638.41, 'due' => '2026-04-10', 'status' => 'Pago',     'pay' => $pix?->id],
        ];

        foreach ($seedData as $item) {
            $category = Category::where('name', $item['category'])->first();
            if (!$category) continue;

            Expense::firstOrCreate(
                [
                    'date' => $item['date'],
                    'category_id' => $category->id,
                    'description' => $item['desc'],
                    'value' => $item['value'],
                ],
                [
                    'due_date' => $item['due'],
                    'status' => $item['status'],
                    'payment_method_id' => $item['pay'],
                    'notes' => '',
                ]
            );
        }
    }
}
