@extends('layouts.app')

@php $currentTab = 'relatorio'; @endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Relatório de Gastos</h2>
            <p class="text-sm text-slate-500">Selecione o mês para gerar o relatório.</p>
        </div>
        <form method="GET" action="{{ route('reports.index') }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3">
            <div class="flex flex-wrap items-end gap-3">
                <div class="flex items-center gap-3">
                    <label class="text-xs font-semibold text-slate-500 uppercase">Mês:</label>
                    <input type="month" name="month" value="{{ $month }}" class="bg-white border border-slate-300 rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-700">
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs text-slate-500">Status</label>
                    <select name="status" class="bg-white border border-slate-300 rounded-lg px-3 py-1 text-sm">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Todos</option>
                        <option value="Pendente" {{ request('status') === 'Pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="Pago" {{ request('status') === 'Pago' ? 'selected' : '' }}>Pago</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs text-slate-500">Forma Pgto</label>
                    <select name="payment_method_id" class="bg-white border border-slate-300 rounded-lg px-3 py-1 text-sm">
                        <option value="">Todos</option>
                        @foreach($paymentMethods ?? [] as $pm)
                            <option value="{{ $pm->id }}" {{ request('payment_method_id') == $pm->id ? 'selected' : '' }}>{{ $pm->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs text-slate-500">Categoria</label>
                    <select name="category_id" class="bg-white border border-slate-300 rounded-lg px-3 py-1 text-sm">
                        <option value="">Todas</option>
                        @foreach($categories ?? [] as $c)
                            <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs text-slate-500">Venc. de</label>
                    <input type="date" name="due_from" value="{{ request('due_from') }}" class="bg-white border border-slate-300 rounded-lg px-3 py-1 text-sm">
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs text-slate-500">até</label>
                    <input type="date" name="due_to" value="{{ request('due_to') }}" class="bg-white border border-slate-300 rounded-lg px-3 py-1 text-sm">
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs text-slate-500">Valor min</label>
                    <input type="number" step="0.01" name="min_value" value="{{ request('min_value') }}" class="w-28 bg-white border border-slate-300 rounded-lg px-3 py-1 text-sm">
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs text-slate-500">max</label>
                    <input type="number" step="0.01" name="max_value" value="{{ request('max_value') }}" class="w-28 bg-white border border-slate-300 rounded-lg px-3 py-1 text-sm">
                </div>

                <div class="ml-auto">
                    <button type="submit" class="bg-sky-600 text-white px-4 py-1.5 rounded-lg text-sm font-semibold">Filtrar</button>
                    <a href="{{ route('reports.index', ['month' => $month]) }}" class="ml-2 text-sm text-slate-500">Limpar</a>
                    <form method="GET" action="{{ route('reports.export') }}" class="inline-block ml-3">
                        @foreach(request()->query() as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <button type="submit" class="bg-slate-800 text-white px-3 py-1.5 rounded-lg text-sm">Exportar CSV</button>
                    </form>
                </div>
            </div>
        </form>
        
        @php
            $hasFilters = request()->filled('status') || request()->filled('payment_method_id') || request()->filled('category_id') || request()->filled('due_from') || request()->filled('due_to') || request()->filled('min_value') || request()->filled('max_value');
        @endphp

        @if($hasFilters)
            <div class="mt-3 flex flex-wrap gap-2">
                @if(request()->filled('status') && request('status') !== 'all')
                    <span class="inline-flex items-center rounded-md bg-amber-50 text-amber-700 px-2 py-0.5 text-xs">Status: {{ request('status') }}</span>
                @endif

                @if(request()->filled('payment_method_id'))
                    <span class="inline-flex items-center rounded-md bg-slate-50 text-slate-700 px-2 py-0.5 text-xs">Pgto: {{ optional($paymentMethods->firstWhere('id', request('payment_method_id')))->name }}</span>
                @endif

                @if(request()->filled('category_id'))
                    <span class="inline-flex items-center rounded-md bg-slate-50 text-slate-700 px-2 py-0.5 text-xs">Categoria: {{ optional($categories->firstWhere('id', request('category_id')))->name }}</span>
                @endif

                @if(request()->filled('due_from') || request()->filled('due_to'))
                    <span class="inline-flex items-center rounded-md bg-slate-50 text-slate-700 px-2 py-0.5 text-xs">Venc.: {{ request('due_from') ?: '...'}} - {{ request('due_to') ?: '...' }}</span>
                @endif

                @if(request()->filled('min_value') || request()->filled('max_value'))
                    <span class="inline-flex items-center rounded-md bg-slate-50 text-slate-700 px-2 py-0.5 text-xs">Valor: {{ request('min_value') ?: '0' }} - {{ request('max_value') ?: '∞' }}</span>
                @endif
            </div>
        @endif
    </div>

    <div class="overflow-x-auto bg-white rounded-xl p-4">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500">
                    <th class="px-3 py-3 text-left">Data</th>
                    <th class="px-3 py-3 text-left">Categoria</th>
                    <th class="px-3 py-3 text-left">Descrição</th>
                    <th class="px-3 py-3 text-right">Valor</th>
                    <th class="px-3 py-3 text-center">Vencimento</th>
                    <th class="px-3 py-3 text-center">Status</th>
                    <th class="px-3 py-3 text-center">Pgto</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($expenses as $e)
                    <tr class="hover:bg-slate-50">
                        <td class="px-3 py-2.5 text-slate-600 text-xs">{{ $e->date->format('d/m/Y') }}</td>
                        <td class="px-3 py-2.5 font-medium text-slate-800 text-xs">{{ $e->category->name ?? '-' }}</td>
                        <td class="px-3 py-2.5 text-slate-500 text-xs">{{ $e->description ?: '-' }}</td>
                        <td class="px-3 py-2.5 text-right font-semibold text-slate-800 text-xs">R$ {{ number_format($e->value, 2, ',', '.') }}</td>
                        <td class="px-3 py-2.5 text-center text-xs text-slate-500">{{ $e->due_date ? $e->due_date->format('d/m/Y') : '-' }}</td>
                        <td class="px-3 py-2.5 text-center text-xs">
                            @if($e->status === 'Pago')
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">Pago</span>
                            @else
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-amber-600/20">Pendente</span>
                            @endif
                        </td>
                        <td class="px-3 py-2.5 text-center text-xs text-slate-500">{{ $e->paymentMethod->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-400 text-sm">Nenhum gasto para este mês.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
