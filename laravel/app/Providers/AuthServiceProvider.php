<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Eloquents\Friend' => 'App\Policies\FriendPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // パスワードグラントのみ利用
        Passport::routes(function($router){
            $router->forAccessTokens();
            // $router->forAuthorization();
            // $router->forTransientTokens();
        }, ['prefix' => 'api/oauth']);
    }
}
