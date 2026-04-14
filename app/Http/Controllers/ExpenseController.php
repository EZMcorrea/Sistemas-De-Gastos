<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $year = (int) substr($month, 0, 4);
        $monthNum = (int) substr($month, 5, 2);

        // show only pending by default; allow '?status=all' to show all
        $status = $request->get('status', 'Pendente');

        $query = Expense::with(['category', 'paymentMethod'])
            ->whereYear('date', $year)
            ->whereMonth('date', $monthNum)
            ->orderBy('date');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $expenses = $query->get();

        $total = $expenses->sum('value');
        $totalPago = $expenses->where('status', 'Pago')->sum('value');
        $totalPendente = $total - $totalPago;

        $categories = Category::orderBy('name')->get();
        $paymentMethods = PaymentMethod::orderBy('name')->get();

        return view('expenses.index', compact(
            'expenses', 'month', 'total', 'totalPago', 'totalPendente',
            'categories', 'paymentMethods'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
            'value' => 'required|numeric|min:0.01',
            'due_date' => 'nullable|date',
            'status' => 'required|in:Pendente,Pago',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'notes' => 'nullable|string|max:1000',
            'notify_telegram' => 'nullable|boolean',
        ]);

        $expense = Expense::create($validated);

        if ($request->boolean('notify_telegram')) {
            app(\App\Services\TelegramService::class)->notifyNewExpense($expense->load(['category', 'paymentMethod']));
        }

        return redirect()->route('expenses.index', ['month' => substr($validated['date'], 0, 7)])
            ->with('success', 'Gasto adicionado com sucesso!');
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
            'value' => 'required|numeric|min:0.01',
            'due_date' => 'nullable|date',
            'status' => 'required|in:Pendente,Pago',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index', ['month' => substr($validated['date'], 0, 7)])
            ->with('success', 'Gasto atualizado com sucesso!');
    }

    public function destroy(Expense $expense)
    {
        $month = $expense->date->format('Y-m');
        $expense->delete();

        return redirect()->route('expenses.index', ['month' => $month])
            ->with('success', 'Gasto excluído com sucesso!');
    }
}
