<?php

namespace Tests\RequestFactories;


use Illuminate\Support\Str;
use Worksome\RequestFactories\RequestFactory;

class ResetPasswordFormRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'token' => Str::random(60),
            'email' => 'testing@gmail.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];
    }
}
