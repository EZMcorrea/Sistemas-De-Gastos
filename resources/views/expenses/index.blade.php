@extends('layouts.app')

@php $currentTab = 'gastos'; @endphp

@section('header-actions')
    <button onclick="document.getElementById('modalExpense').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition flex items-center justify-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Novo Gasto
    </button>
@endsection

@section('content')
    {{-- Month Filter --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <form method="GET" action="{{ route('expenses.index') }}" class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-lg px-4 py-2">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <label class="text-xs font-semibold text-slate-500 uppercase">Mês:</label>
            <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-700">
        </form>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl p-4 text-white">
            <p class="text-xs font-medium text-blue-100 uppercase tracking-wider">Total Geral</p>
            <p class="text-xl font-bold mt-1">R$ {{ number_format($total, 2, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl p-4 text-white">
            <p class="text-xs font-medium text-emerald-100 uppercase tracking-wider">Total Pago</p>
            <p class="text-xl font-bold mt-1">R$ {{ number_format($totalPago, 2, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-amber-700 rounded-xl p-4 text-white">
            <p class="text-xs font-medium text-amber-100 uppercase tracking-wider">Total Pendente</p>
            <p class="text-xl font-bold mt-1">R$ {{ number_format($totalPendente, 2, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Lançamentos</p>
            <p class="text-xl font-bold text-slate-800 mt-1">{{ $expenses->count() }}</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
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
                    <th class="px-3 py-3 text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($expenses as $expense)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-3 py-2.5 text-slate-600 text-xs whitespace-nowrap">{{ $expense->date->format('d/m/Y') }}</td>
                        <td class="px-3 py-2.5 font-medium text-slate-800 text-xs">{{ $expense->category->name }}</td>
                        <td class="px-3 py-2.5 text-slate-500 text-xs truncate max-w-[100px]">{{ $expense->description ?: '-' }}</td>
                        <td class="px-3 py-2.5 text-right font-semibold text-slate-800 text-xs whitespace-nowrap">R$ {{ number_format($expense->value, 2, ',', '.') }}</td>
                        <td class="px-3 py-2.5 text-center text-xs text-slate-500">{{ $expense->due_date ? $expense->due_date->format('d/m/Y') : '-' }}</td>
                        <td class="px-3 py-2.5 text-center">
                            @if($expense->status === 'Pago')
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">Pago</span>
                            @else
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-amber-600/20">Pendente</span>
                            @endif
                        </td>
                        <td class="px-3 py-2.5 text-center text-xs text-slate-500">{{ $expense->paymentMethod->name ?? '-' }}</td>
                        <td class="px-3 py-2.5 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button onclick="openEditExpense({{ $expense->id }})" class="text-slate-400 hover:text-blue-600 p-1 rounded hover:bg-blue-50">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button onclick="confirmDelete('{{ route('expenses.destroy', $expense) }}')" class="text-slate-400 hover:text-red-600 p-1 rounded hover:bg-red-50">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-slate-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <p class="text-sm">Nenhum gasto registrado para este mês.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal: Novo Gasto --}}
    <div id="modalExpense" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="modal-overlay absolute inset-0" onclick="document.getElementById('modalExpense').classList.add('hidden')"></div>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative z-10 fade-in max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white rounded-t-2xl">
                <h3 class="text-lg font-bold text-slate-900" id="expenseModalTitle">Novo Gasto</h3>
                <button onclick="document.getElementById('modalExpense').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1 rounded-full hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="expenseForm" method="POST" action="{{ route('expenses.store') }}" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" value="POST" id="expenseMethod">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Data do Gasto *</label>
                        <input type="date" name="date" id="expDate" value="{{ $month }}-01" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Vencimento</label>
                        <input type="date" name="due_date" id="expDue" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Categoria *</label>
                    <select name="category_id" id="expCategory" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                        <option value="" disabled selected>Selecione a categoria...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Descrição</label>
                        <input type="text" name="description" id="expDesc" placeholder="Ex: Mercado Pago" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Valor (R$) *</label>
                        <input type="number" name="value" id="expValue" step="0.01" min="0.01" required placeholder="0,00" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
                        <select name="status" id="expStatus" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                            <option value="Pendente">Pendente</option>
                            <option value="Pago">Pago</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Forma de Pagamento</label>
                        <select name="payment_method_id" id="expPayMethod" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($paymentMethods as $pm)
                                <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Observações</label>
                    <input type="text" name="notes" id="expNotes" placeholder="Detalhes adicionais..." class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                </div>

                <div class="flex items-center gap-2 p-3 bg-sky-50 rounded-lg border border-sky-100" id="telegramCheckbox">
                    <input type="checkbox" name="notify_telegram" value="1" checked class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                    <label class="text-sm font-medium text-sky-700 cursor-pointer">Enviar notificação para o Telegram</label>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modalExpense').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2.5 rounded-lg transition text-sm">Cancelar</button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg shadow-sm transition text-sm">Salvar Gasto</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Dados dos gastos para edição via JS
    @php
        $expensesJson = $expenses->map(function($e) {
            return [
                'id' => $e->id,
                'date' => $e->date->format('Y-m-d'),
                'due_date' => $e->due_date ? $e->due_date->format('Y-m-d') : null,
                'category_id' => $e->category_id,
                'description' => $e->description,
                'value' => $e->value,
                'status' => $e->status,
                'payment_method_id' => $e->payment_method_id,
                'notes' => $e->notes,
            ];
        })->toJson();
    @endphp

    const expensesData = {!! $expensesJson !!};

    function openEditExpense(id) {
        const exp = expensesData.find(e => e.id === id);
        if (!exp) return;

        document.getElementById('expenseModalTitle').textContent = 'Editar Gasto';
        document.getElementById('expenseForm').action = '/gastos/' + id;
        document.getElementById('expenseMethod').value = 'PUT';
        document.getElementById('expDate').value = exp.date;
        document.getElementById('expDue').value = exp.due_date || '';
        document.getElementById('expCategory').value = exp.category_id;
        document.getElementById('expDesc').value = exp.description || '';
        document.getElementById('expValue').value = exp.value;
        document.getElementById('expStatus').value = exp.status;
        document.getElementById('expPayMethod').value = exp.payment_method_id || '';
        document.getElementById('expNotes').value = exp.notes || '';
        document.getElementById('telegramCheckbox').classList.add('hidden');

        document.getElementById('modalExpense').classList.remove('hidden');
    }

    // Reset modal ao abrir para novo
    document.querySelector('[onclick="document.getElementById(\'modalExpense\').classList.remove(\'hidden\')"]')?.addEventListener('click', function() {
        document.getElementById('expenseModalTitle').textContent = 'Novo Gasto';
        document.getElementById('expenseForm').action = '{{ route("expenses.store") }}';
        document.getElementById('expenseMethod').value = 'POST';
        document.getElementById('expenseForm').reset();
        document.getElementById('expDate').value = '{{ $month }}-01';
        document.getElementById('telegramCheckbox').classList.remove('hidden');
    });
</script>
@endpush
