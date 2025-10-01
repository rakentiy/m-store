<?php

declare(strict_types=1);

use App\Support\Flash\Flash;

if (!function_exists('flash')) {
    function flash(): Flash
    {
        return app(Flash::class);
    }
}

if (!function_exists('getEmailNamePart')) {
    /**
     * Возвращает часть email до @
     *
     * @param string $email
     * @return string
     */
    function getEmailNamePart(string $email): string
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return strstr($email, '@', true);
        }

        return '';
    }
}
