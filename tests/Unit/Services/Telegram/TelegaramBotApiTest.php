<?php

use Illuminate\Support\Facades\Http;
use Services\Telegram\TelegramBotApi;

it('sends message success', function () {
    Http::fake([
        TelegramBotApi::HOST . '*' => Http::response(['ok' => true]),
    ]);

    $result = TelegramBotApi::sendMessage('', 1, 'Testing');
    $this->assertTrue($result);
});
