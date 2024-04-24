<?php

namespace App\Providers;


use App\Models\InstrumentSupported;
use App\Models\User;
use App\Repositories\Contracts\InstrumentSupportedRepository;
use App\Repositories\Contracts\UserInterface;
use App\Repositories\EloquentInstrumentSupportedRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserInterface::class, fn() => new UserRepository(new User()));
    }
}
