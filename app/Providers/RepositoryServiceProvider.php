<?php

namespace App\Providers;


use App\Models\Category;
use App\Models\Floor;
use App\Models\InstrumentSupported;
use App\Models\Membership;
use App\Models\MembershipType;
use App\Models\Parking;
use App\Models\ParkingRate;
use App\Models\Place;
use App\Models\Slot;
use App\Models\Tariff;
use App\Models\User;
use App\Models\Vehicle;
use App\Repositories\CategoryRepository;
use App\Repositories\Contracts\CategoryInterface;
use App\Repositories\Contracts\FloorInterface;
use App\Repositories\Contracts\InstrumentSupportedRepository;
use App\Repositories\Contracts\MembershipInterface;
use App\Repositories\Contracts\MembershipTypeInterface;
use App\Repositories\Contracts\ParkingInterface;
use App\Repositories\Contracts\ParkingRateInterface;
use App\Repositories\Contracts\PlaceInterface;
use App\Repositories\Contracts\SlotInterface;
use App\Repositories\Contracts\TariffInterface;
use App\Repositories\Contracts\UserInterface;
use App\Repositories\Contracts\VehicleInterface;
use App\Repositories\EloquentInstrumentSupportedRepository;
use App\Repositories\FloorRepository;
use App\Repositories\MembershipRepository;
use App\Repositories\MembershipTypeRepository;
use App\Repositories\ParkingRateRepository;
use App\Repositories\ParkingRepository;
use App\Repositories\PlaceRepository;
use App\Repositories\SlotRepository;
use App\Repositories\TariffRepository;
use App\Repositories\UserRepository;
use App\Repositories\VehicleRepository;
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
    public function register(): void
    {
        $this->app->bind(UserInterface::class, fn() => new UserRepository(new User()));
        $this->app->bind(CategoryInterface::class, fn() => new CategoryRepository(new Category()));
        $this->app->bind(FloorInterface::class, fn() => new FloorRepository(new Floor()));
        $this->app->bind(ParkingInterface::class, fn() => new ParkingRepository(new Parking()));
        $this->app->bind(ParkingRateInterface::class, fn() => new ParkingRateRepository(new ParkingRate()));
        $this->app->bind(SlotInterface::class, fn() => new SlotRepository(new Slot()));
        $this->app->bind(TariffInterface::class, fn() => new TariffRepository(new Tariff()));
        $this->app->bind(PlaceInterface::class, fn() => new PlaceRepository(new Place()));
        $this->app->bind(VehicleInterface::class, fn() => new VehicleRepository(new Vehicle()));
        $this->app->bind(MembershipInterface::class, fn() => new MembershipRepository(new Membership()));
        $this->app->bind(MembershipTypeInterface::class, fn() => new MembershipTypeRepository(new MembershipType()));
    }
}
