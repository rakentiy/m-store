<?php

use App\Faker\FakerImageProvider;
use Faker\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('web', [
            'throttle:global',
        ]);

        $middleware->appendToGroup('api', [
            'throttle:api',
        ]);
    })
    ->withSingletons([
        \Faker\Generator::class => function () {
            $faker = Factory::create();
            $faker->addProvider(new FakerImageProvider($faker));
            return $faker;
        },
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
