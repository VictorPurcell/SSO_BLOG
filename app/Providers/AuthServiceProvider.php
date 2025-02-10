<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Container\Attributes\Auth;
use Symfony\Component\Routing\Route;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * O mapa de políticas para a aplicação.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Registra quaisquer serviços de autenticação/autorização.
     */

    public function boot()
    {
        $this->registerPolicies();

        Passport::routes(); // Registra as rotas do Passport

        Passport::tokensCan([
        'profile' => 'Acessar perfil do usuário',
        'email' => 'Acessar email do usuário',
    ]);

        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        Passport::refreshTokensExpireIn(now()->addYear(1));

        Passport::enableImplicitGrant();
    }


}
