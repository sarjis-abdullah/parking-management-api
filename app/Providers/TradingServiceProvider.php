<?php

namespace App\Providers;

use App\Services\Trading\Contracts\TradingClient;
use App\Services\Trading\Contracts\TradingInstrument;
use App\Services\Trading\Contracts\TradingOrder;
use App\Services\Trading\Contracts\TradingPayment;
use App\Services\Trading\Contracts\TradingUser;
use App\Services\Trading\Upvest\UpvestTradingClient;
use App\Services\Trading\Upvest\UpvestTradingInstrument;
use App\Services\Trading\Upvest\UpvestTradingOrder;
use App\Services\Trading\Upvest\UpvestTradingPayment;
use App\Services\Trading\Upvest\UpvestTradingUser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class TradingServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TradingClient::class, function (Application $app) {
            return new UpvestTradingClient();
        });

        $this->app->bind(TradingUser::class, function (Application $app) {
            return new UpvestTradingUser();
        });

        $this->app->bind(TradingInstrument::class, function (Application $app) {
            return new UpvestTradingInstrument();
        });

        $this->app->bind(TradingOrder::class, function (Application $app) {
            return new UpvestTradingOrder();
        });

        $this->app->bind(TradingPayment::class, function (Application $app) {
            return new UpvestTradingPayment();
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
