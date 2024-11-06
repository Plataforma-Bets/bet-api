<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * O mapa de políticas para a aplicação.
     *
     * @var array
     */
    protected $policies = [
        // Registre as políticas da sua aplicação aqui
    ];

    /**
     * Registre quaisquer serviços de autenticação ou autorização.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Registre outras regras de autorização ou autenticação aqui, se necessário
    }
}