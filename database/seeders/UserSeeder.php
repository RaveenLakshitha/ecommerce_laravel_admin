<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::unguard();

        $customers = [
            ['first_name' => 'John', 'last_name' => 'Doe', 'phone' => '555-0101'],
            ['first_name' => 'Jane', 'last_name' => 'Smith', 'phone' => '555-0102'],
            ['first_name' => 'Michael', 'last_name' => 'Johnson', 'phone' => '555-0103'],
            ['first_name' => 'Emily', 'last_name' => 'Davis', 'phone' => '555-0104'],
            ['first_name' => 'David', 'last_name' => 'Wilson', 'phone' => '555-0105'],
        ];

        foreach ($customers as $c) {
            $name = $c['first_name'] . ' ' . $c['last_name'];
            $email = Str::slug($name, '.') . '@example.com';

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                ]
            );

            // Assign role if exists
            if (Role::where('name', 'customer')->exists()) {
                $user->assignRole('customer');
            }

            // Create or update Customer profile
            \App\Models\Customer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $c['first_name'],
                    'last_name' => $c['last_name'],
                    'email' => $email,
                    'phone' => $c['phone'],
                ]
            );
        }

        User::reguard();
    }
}