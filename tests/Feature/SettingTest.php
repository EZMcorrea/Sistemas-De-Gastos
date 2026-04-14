<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_and_retrieve_telegram_settings()
    {
        $response = $this->put('/configuracoes', [
            'telegram_token' => '123:ABC',
            'telegram_chat_id' => '987654',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('settings', ['key' => 'telegram_token', 'value' => '123:ABC']);
        $this->assertDatabaseHas('settings', ['key' => 'telegram_chat_id', 'value' => '987654']);

        $this->assertEquals('123:ABC', Setting::getValue('telegram_token'));
        $this->assertEquals('987654', Setting::getValue('telegram_chat_id'));
    }
}
