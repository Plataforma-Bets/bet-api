<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Caminho para a página inicial da sua aplicação.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Defina suas rotas.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Defina o controle de acesso.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        //
    }
}