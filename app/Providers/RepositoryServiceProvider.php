<?php

namespace App\Providers;


use App\Models\InstrumentSupported;
use App\Repositories\Contracts\InstrumentSupportedRepository;
use App\Repositories\EloquentInstrumentSupportedRepository;
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
        $this->app->bind(InstrumentSupportedRepository::class, fn() => new EloquentInstrumentSupportedRepository(new InstrumentSupported()));


    }
}
