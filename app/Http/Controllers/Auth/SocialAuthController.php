<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Models\User;
use DomainException;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    public function redirect(string $driver)
    {
        try {
            return Socialite::driver($driver)
                ->redirect();
        } catch (Throwable $exception) {
            throw new DomainException('Произошла ошибка или драйвер не поддерживается.');
        }
    }


    public function callback(string $driver)
    {
        if ($driver !== 'github') {
            throw new DomainException('Драйвер не поддерживается.');
        }

        $socialUser = Socialite::driver($driver)->user();

        $user = User::query()->updateOrCreate([
            $driver . '_id' => $socialUser->id,
        ], [
            'name' => $socialUser->name
                ?? getEmailNamePart($socialUser->email),
            'email' => $socialUser->email,
            'password' => bcrypt(str()->random(20)),
        ]);

        auth()->login($user);

        return redirect()
            ->intended(route('home'));
    }
}
