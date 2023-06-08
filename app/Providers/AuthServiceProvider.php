<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Auth\TokenUserProvider;
use App\Auth\TokenGuard;
use App\Models\UserToken;
use App\Policies\UserPolicy;
use App\Services\AuthServices;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Gate::policy(User::class, UserPolicy::class);
        // Gate::define('create', function (User $user, $bool) {
        //     return $bool ? true : false;
        // });

        Gate::define('create-token', [UserPolicy::class, 'create']);
        // Gate::define('login', function (User $user, $authUser) {

        //     return UserToken::where('user_id', $authUser?->id)->first() ? false : true;
        // });

        // Gate::define('login', function (User $user,  $bool) {
        //     return $bool;
        // });

        // Gate::policy(User::class, UserPolicy::class);

        Auth::extend('token', function ($app, $name, array $config) {
            return new TokenGuard(Auth::createUserProvider($config['provider']), $app['request']);
        });
        Auth::provider('user_tokens', function ($app, array $config) {
            return new TokenUserProvider();
        });
    }
}
