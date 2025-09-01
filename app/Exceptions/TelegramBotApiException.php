<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

final class TelegramBotApiException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = "Telegram API request failed: {$message}";

        return parent::__construct($message, $code, $previous);
    }

    public function report()
    {
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json([]);
    }
}
