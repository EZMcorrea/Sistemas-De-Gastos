@extends('layouts.app')

@php $currentTab = 'resumo'; @endphp

@section('content')
    {{-- Month Filter --}}
    <div class="flex items-center gap-3 mb-6">
        <form method="GET" action="{{ route('summary.index') }}" class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-lg px-4 py-2">
            <label class="text-xs font-semibold text-slate-500 uppercase">Mês:</label>
            <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-700">
        </form>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl p-4 text-white">
            <p class="text-xs font-medium text-blue-100 uppercase tracking-wider">Total Geral</p>
            <p class="text-2xl font-bold mt-1">R$ {{ number_format($total, 2, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl p-4 text-white">
            <p class="text-xs font-medium text-emerald-100 uppercase tracking-wider">Total Pago</p>
            <p class="text-2xl font-bold mt-1">R$ {{ number_format($totalPago, 2, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-amber-700 rounded-xl p-4 text-white">
            <p class="text-xs font-medium text-amber-100 uppercase tracking-wider">Total Pendente</p>
            <p class="text-2xl font-bold mt-1">R$ {{ number_format($totalPendente, 2, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Lançamentos</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $count }}</p>
        </div>
    </div>

    {{-- Table por Categoria --}}
    <h3 class="text-base font-bold text-slate-800 mb-3">Total por Categoria</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500">
                    <th class="px-3 py-3 text-left">Categoria</th>
                    <th class="px-3 py-3 text-right">Total (R$)</th>
                    <th class="px-3 py-3 text-right">Pago (R$)</th>
                    <th class="px-3 py-3 text-right">Pendente (R$)</th>
                    <th class="px-3 py-3 text-center">% do Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($byCategory as $cat)
                    <tr class="hover:bg-slate-50">
                        <td class="px-3 py-2 font-medium text-slate-700 text-xs">{{ $cat['name'] }}</td>
                        <td class="px-3 py-2 text-right font-semibold text-slate-800 text-xs">R$ {{ number_format($cat['total'], 2, ',', '.') }}</td>
                        <td class="px-3 py-2 text-right text-emerald-600 text-xs">R$ {{ number_format($cat['pago'], 2, ',', '.') }}</td>
                        <td class="px-3 py-2 text-right text-amber-600 text-xs">R$ {{ number_format($cat['pendente'], 2, ',', '.') }}</td>
                        <td class="px-3 py-2 text-center text-slate-500 text-xs font-medium">{{ $cat['percent'] }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate-400 text-sm">Nenhum dado para este mês.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
