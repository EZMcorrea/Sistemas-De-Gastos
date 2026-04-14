<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'month' => ['nullable', 'regex:/^\d{4}-\d{2}$/'],
            'status' => ['nullable', 'in:Pendente,Pago,all'],
            'payment_method_id' => ['nullable', 'integer', 'exists:payment_methods,id'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'due_from' => ['nullable', 'date'],
            'due_to' => ['nullable', 'date'],
            'min_value' => ['nullable', 'numeric'],
            'max_value' => ['nullable', 'numeric'],
        ]);

        $month = $data['month'] ?? now()->format('Y-m');
        $year = (int) substr($month, 0, 4);
        $monthNum = (int) substr($month, 5, 2);

        // build and execute filtered query
        $query = $this->buildFilteredQuery($request, $year, $monthNum);
        $expenses = $query->orderBy('date')->get();

        $categories = Category::orderBy('name')->get();
        $paymentMethods = PaymentMethod::orderBy('name')->get();

        return view('reports.index', compact('expenses', 'month', 'categories', 'paymentMethods'));
    }

    public function export(Request $request)
    {
        $data = $request->validate([
            'month' => ['nullable', 'regex:/^\d{4}-\d{2}$/'],
            'status' => ['nullable', 'in:Pendente,Pago,all'],
            'payment_method_id' => ['nullable', 'integer', 'exists:payment_methods,id'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'due_from' => ['nullable', 'date'],
            'due_to' => ['nullable', 'date'],
            'min_value' => ['nullable', 'numeric'],
            'max_value' => ['nullable', 'numeric'],
        ]);

        $month = $data['month'] ?? now()->format('Y-m');
        $year = (int) substr($month, 0, 4);
        $monthNum = (int) substr($month, 5, 2);

        $query = $this->buildFilteredQuery($request, $year, $monthNum);

        $filename = sprintf('relatorio-%s.csv', $month);

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Data', 'Categoria', 'Descricao', 'Valor', 'Vencimento', 'Status', 'Forma Pgto']);
            foreach ($query->with(['category', 'paymentMethod'])->orderBy('date')->cursor() as $e) {
                fputcsv($handle, [
                    $e->date->format('Y-m-d'),
                    $e->category->name ?? '',
                    $e->description ?? '',
                    number_format($e->value, 2, '.', ''),
                    $e->due_date ? $e->due_date->format('Y-m-d') : '',
                    $e->status,
                    $e->paymentMethod->name ?? '',
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function buildFilteredQuery(Request $request, int $year, int $monthNum)
    {
        $query = Expense::with(['category', 'paymentMethod'])
            ->whereYear('date', $year)
            ->whereMonth('date', $monthNum);

        $status = $request->get('status');
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($paymentMethodId = $request->get('payment_method_id')) {
            $query->where('payment_method_id', $paymentMethodId);
        }

        if ($categoryId = $request->get('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($dueFrom = $request->get('due_from')) {
            $query->whereDate('due_date', '>=', $dueFrom);
        }

        if ($dueTo = $request->get('due_to')) {
            $query->whereDate('due_date', '<=', $dueTo);
        }

        if (($minValue = $request->get('min_value')) !== null && $minValue !== '') {
            $query->where('value', '>=', (float) $minValue);
        }

        if (($maxValue = $request->get('max_value')) !== null && $maxValue !== '') {
            $query->where('value', '<=', (float) $maxValue);
        }

        return $query;
    }
}
