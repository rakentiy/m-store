<?php

namespace App\Providers;

use App\Contracts\RouteRegistrar;
use App\Routing\AppRegistrar;
use Domain\Auth\Routing\AuthRegistrar;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use RuntimeException;

class RouteServiceProvider extends ServiceProvider
{
    protected array $registrars = [
        AppRegistrar::class,
        AuthRegistrar::class,
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->routes(function (Registrar $router) {
            $this->mapWebRoutes($router, $this->registrars);
        });
    }

    protected function mapWebRoutes(Registrar $router, array $registrars): void
    {
        foreach ($registrars as $registrar) {
            if (!class_exists($registrar) || !is_subclass_of($registrar, RouteRegistrar::class)) {
                throw new RuntimeException(
                    sprintf(
                        'Cannot map routes \'%s\', it is not a valid router class',
                        $registrar
                    )
                );
            }
            (new $registrar)->map($router);
        }
    }
}
