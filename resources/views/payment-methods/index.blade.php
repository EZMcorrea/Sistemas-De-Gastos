@extends('layouts.app')

@php $currentTab = 'pagamentos'; @endphp

@section('header-actions')
    <button onclick="openPayModal()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition flex items-center justify-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Nova Forma
    </button>
@endsection

@section('content')
    <div class="mb-6">
        <h2 class="text-lg font-bold text-slate-900">Formas de Pagamento</h2>
        <p class="text-sm text-slate-500">Adicione, edite ou remova formas de pagamento.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        @foreach($paymentMethods as $pm)
            <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 hover:border-blue-300 transition group">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-sm font-medium text-slate-700">{{ $pm->name }}</span>
                </div>
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                    <button onclick="openPayModal({{ $pm->id }}, '{{ addslashes($pm->name) }}')" class="text-slate-400 hover:text-blue-600 p-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <button onclick="confirmDelete('{{ route('payment-methods.destroy', $pm) }}')" class="text-slate-400 hover:text-red-600 p-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Modal: Nova/Editar Forma de Pagamento --}}
    <div id="modalPayment" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="modal-overlay absolute inset-0" onclick="document.getElementById('modalPayment').classList.add('hidden')"></div>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm relative z-10 fade-in">
            <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900" id="payModalTitle">Nova Forma de Pagamento</h3>
                <button onclick="document.getElementById('modalPayment').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1 rounded-full hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="payForm" method="POST" action="{{ route('payment-methods.store') }}" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" value="POST" id="payMethod">
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Nome da Forma de Pagamento *</label>
                    <input type="text" name="name" id="payName" required placeholder="Ex: PIX" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modalPayment').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2.5 rounded-lg transition text-sm">Cancelar</button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg shadow-sm transition text-sm">Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openPayModal(id, name) {
        const form = document.getElementById('payForm');
        const title = document.getElementById('payModalTitle');

        if (id) {
            title.textContent = 'Editar Forma de Pagamento';
            form.action = '/pagamentos/' + id;
            document.getElementById('payMethod').value = 'PUT';
            document.getElementById('payName').value = name;
        } else {
            title.textContent = 'Nova Forma de Pagamento';
            form.action = '{{ route("payment-methods.store") }}';
            document.getElementById('payMethod').value = 'POST';
            document.getElementById('payName').value = '';
        }

        document.getElementById('modalPayment').classList.remove('hidden');
    }
</script>
@endpush
