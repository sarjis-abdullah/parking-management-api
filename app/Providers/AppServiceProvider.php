<?php

namespace App\Providers;

use App\Models\Membership;
use App\Observers\MembershipObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Membership::observe(MembershipObserver::class);
    }
}
