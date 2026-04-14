@extends('layouts.app')

@php $currentTab = 'config'; @endphp

@section('content')
    <h2 class="text-lg font-bold text-slate-900 mb-2">Configurações de Integração</h2>
    <p class="text-sm text-slate-500 mb-6">Configure seu Bot do Telegram para receber notificações ao cadastrar novos gastos.</p>

    <form method="POST" action="{{ route('settings.update') }}" class="max-w-md space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Token do Bot</label>
            <input type="text" name="telegram_token" value="{{ old('telegram_token', $telegramToken) }}" placeholder="Ex: 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Chat ID</label>
            <input type="text" name="telegram_chat_id" value="{{ old('telegram_chat_id', $telegramChatId) }}" placeholder="Ex: 123456789" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition">Salvar Configurações</button>

        <div class="mt-4 p-3 bg-sky-50 rounded-lg border border-sky-100 text-xs text-sky-700">
            <strong>Dica:</strong> Crie um bot em @@BotFather no Telegram para obter o Token. Envie uma mensagem para o bot e use a API getUpdates para obter o Chat ID.
        </div>
    </form>
@endsection
