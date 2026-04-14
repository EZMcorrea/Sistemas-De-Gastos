<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $telegramToken = Setting::getValue('telegram_token', '');
        $telegramChatId = Setting::getValue('telegram_chat_id', '');

        return view('settings.index', compact('telegramToken', 'telegramChatId'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'telegram_token' => 'nullable|string|max:255',
            'telegram_chat_id' => 'nullable|string|max:100',
        ]);

        Setting::setValue('telegram_token', $validated['telegram_token'] ?? '');
        Setting::setValue('telegram_chat_id', $validated['telegram_chat_id'] ?? '');

        return redirect()->route('settings.index')
            ->with('success', 'Configurações salvas com sucesso!');
    }
}
