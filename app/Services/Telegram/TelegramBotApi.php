<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Exceptions\TelegramBotApiException;
use Illuminate\Support\Facades\Http;
use Throwable;

final class TelegramBotApi
{
    public const HOST = 'https://api.telegram.org/bot';

    public static function sendMessage(string $token, int $chatId, string $message): bool
    {
        try {
            $response = Http::post(self::HOST . $token . '/sendMessage', [
                'chat_id' => $chatId,
                'text' => $message,
            ])
                ->throw()
                ->json();


            return $response['ok'] ?? false;
        } catch (Throwable $e) {
            report(new TelegramBotApiException($e->getMessage()));
            return false;
        }
    }
}
