<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Floor;
use App\Models\MembershipType;
use App\Models\ParkingRate;
use App\Models\Place;
use App\Models\Slot;
use App\Models\Tariff;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
//            MembershipTypesSeeder::class
        ]);

//        Place::create([
//            'name' => 'Shyamoli'
//        ]);
//
//        Category::create([
//            'name' => 'Bus'
//        ]);
//
//        Floor::create([
//            'name' => 'Bus',
//            'place_id' => 1,
//        ]);
//
//        Slot::create([
//            "name" => "Slot 2",
//            "place_id" => 1,
//            "floor_id" => 1
//        ]);
//
//        $mt = MembershipType::find(1);
//        $mt2 = MembershipType::find(2);
//        $mt3 = MembershipType::find(3);
//        $mt->update([
//            "name"=>"Corporate",
//            "discount_amount" => "10.00",
//            "discount_type"=>"percentage",
//            "default"=> true
//        ]);
//        $mt2->update([
//            "name"=>"Regular customer",
//            "discount_amount" => "10.00",
//            "discount_type"=>"flat",
//        ]);
//        $mt3->update([
//            "name"=>"Our stuff",
//            "discount_type"=>"free",
//        ]);
//
//        $tariff = Tariff::create([
//            'name' => 'Plan 1',
//            "default"=> true,
//            "type" => "half_hourly",
//        ]);
//
//        ParkingRate::create([
//            "rate" => 30,
//            "tariff_id" => 1,
//        ]);

//        {
//            "name": "qeqe",
//              "type": "half_hourly",
//    "default": true,
//    "payment_rates": [
//        {
//            "rate": 30
//        }
//    ]
//}
    }
}
