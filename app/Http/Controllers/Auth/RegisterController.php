<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegisterFormRequest $request, RegisterNewUserContract $action)
    {
        // TODO make DTO
        $action(
            $request->get('name'),
            $request->get('email'),
            $request->get('password')
        );

        return redirect()
            ->intended(route('home'));
    }
}
