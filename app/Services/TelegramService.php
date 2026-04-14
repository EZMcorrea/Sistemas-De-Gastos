<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    public function notifyNewExpense(Expense $expense): void
    {
        $token = Setting::getValue('telegram_token');
        $chatId = Setting::getValue('telegram_chat_id');

        if (!$token || !$chatId) {
            return;
        }

        $date = $expense->date->format('d/m/Y');
        $category = $expense->category->name ?? 'N/A';
        $desc = $expense->description ?: 'N/A';
        $value = 'R$ ' . number_format($expense->value, 2, ',', '.');
        $status = $expense->status;

        $message = "💰 *Novo Gasto!*\n\n"
            . "📅 {$date}\n"
            . "📂 {$category}\n"
            . "📝 {$desc}\n"
            . "💵 *{$value}*\n"
            . "📊 {$status}";

        try {
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            Log::error('Telegram notification failed: ' . $e->getMessage());
        }
    }
}
