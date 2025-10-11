<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class RegisterFormRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        $password = $this->faker->password(8);
        return [
            'email' => 'user@gmail.com',
            'name' => $this->faker->name,
            'password' => $password,
            'password_confirmation' => $password,
        ];
    }
}
