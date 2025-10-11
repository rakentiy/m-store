<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class LoginFormRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        $password = $this->faker->password(8);
        return [
            'email' => 'user@gmail.com',
            'password' => $password,
        ];
    }
}
