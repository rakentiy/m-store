<?php

use Faker\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Support\Faker\FakerImageProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
//        using: function () {
//            (new RouteServiceProvider(app()))->boot();
//        },
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
        $exceptions->renderable(function (DomainException $e) {
            flash()->alert($e->getMessage());
            return back();
        });
    })->create();
