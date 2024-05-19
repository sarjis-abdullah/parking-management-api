<?php

namespace Database\Seeders;

use App\Enums\MembershipType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use function App\Enums\getMembershipPoints;


class MembershipTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $membershipTypes = MembershipType::cases();

        foreach ($membershipTypes as $type) {
            DB::table('membership_types')->insert([
                'name' => $type->value,
                'min_points' => getMembershipPoints($type),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
