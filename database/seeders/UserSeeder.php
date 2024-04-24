<?php

namespace Database\Seeders;

use App\Enums\RolesAndPermissions;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        $this->createUser(
            'Super Admin|Khalid', 'Chatar', 'khalid@morkramer.com', 'Start123!', RolesAndPermissions::SUPER_ADMIN
        );
        $this->createUser(
            'Admin|Khalid', 'Chatar', 'khalid@gmail.com', 'Start123!', RolesAndPermissions::ADMIN
        );
        $this->createUser(
            'Operator|Nishan', 'Chatar', 'khalid@gmail.com', 'Start123!', RolesAndPermissions::ADMIN
        );
    }


    public function createUser(string $name, string $surname, string $email, string $password, ...$roles)
    {
        $this->createUserWith(
            collect([
                'name'     => $name,
                'surname'  => $surname,
                'email'    => $email,
                'password' => Hash::make(
                    $password
                ),
            ]),
            collect(
                $roles
            )
        );
    }

    private function createUserWith(Collection $data, Collection $roles): User
    {
        // IMPORTANT: Business|Private Customer & Home Buyers start also with a role of an Investor and Customer
        if ($roles->contains(RolesAndPermissions::BUSINESS_CUSTOMER) || $roles->contains(
                RolesAndPermissions::PRIVATE_CUSTOMER
            ) || $roles->contains(RolesAndPermissions::HOME_BUYER))
            $roles->push(RolesAndPermissions::INVESTOR, RolesAndPermissions::CUSTOMER);
        else
            $roles->push(RolesAndPermissions::EMPLOYEE);


        $user = User::factory()
                    ->has(UserSetting::factory())
                    ->create($data->toArray());

        $user->assignRole($roles->toArray());

        return $user;
    }
}
