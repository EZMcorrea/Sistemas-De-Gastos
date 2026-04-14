<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('name')->get();
        return view('payment-methods.index', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:payment_methods,name',
        ]);

        PaymentMethod::create($validated);

        return redirect()->route('payment-methods.index')
            ->with('success', 'Forma de pagamento criada com sucesso!');
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:payment_methods,name,' . $paymentMethod->id,
        ]);

        $paymentMethod->update($validated);

        return redirect()->route('payment-methods.index')
            ->with('success', 'Forma de pagamento atualizada com sucesso!');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->expenses()->exists()) {
            return redirect()->route('payment-methods.index')
                ->with('error', 'Não é possível excluir: existem gastos vinculados a esta forma de pagamento.');
        }

        $paymentMethod->delete();

        return redirect()->route('payment-methods.index')
            ->with('success', 'Forma de pagamento excluída com sucesso!');
    }
}
