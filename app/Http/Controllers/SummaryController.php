<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SummaryController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $year = (int) substr($month, 0, 4);
        $monthNum = (int) substr($month, 5, 2);

        $expenses = Expense::with('category')
            ->whereYear('date', $year)
            ->whereMonth('date', $monthNum)
            ->get();

        $total = $expenses->sum('value');
        $totalPago = $expenses->where('status', 'Pago')->sum('value');
        $totalPendente = $total - $totalPago;
        $count = $expenses->count();

        // Agrupar por categoria
        $byCategory = $expenses->groupBy(fn($e) => $e->category->name ?? 'Sem Categoria')
            ->map(function ($items, $catName) use ($total) {
                $catTotal = $items->sum('value');
                $catPago = $items->where('status', 'Pago')->sum('value');
                return [
                    'name' => $catName,
                    'total' => $catTotal,
                    'pago' => $catPago,
                    'pendente' => $catTotal - $catPago,
                    'percent' => $total > 0 ? round(($catTotal / $total) * 100, 1) : 0,
                ];
            })
            ->sortByDesc('total')
            ->values();

        return view('summary.index', compact('month', 'total', 'totalPago', 'totalPendente', 'count', 'byCategory'));
    }
}
