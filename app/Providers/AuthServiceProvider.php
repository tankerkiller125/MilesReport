<?php

namespace App\Providers;

use Carbon\Carbon;
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
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addDays(15));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        Passport::tokensCan([
            'create-event' => 'Can create events for your account',
            'update-event' => 'Can update events for your account',
            'delete-event' => 'Can delete events for your account',
            'create-location' => 'Can create locations',
            'update-location' => 'Can update locations',
            'delete-location' => 'Can delete locations'
        ]);
    }
}
