<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin'),
                'raw_password' => 'superadmin',
                'group' => 'superadmin',
                'phone' => '00000000000',
                'gender' => 'male',
                'address' => 'Jakarta',
                'city' => 'Jakarta',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin'),
                'raw_password' => 'admin',
                'group' => 'admin',
                'phone' => '00000000001',
                'gender' => 'male',
                'address' => 'Jakarta',
                'city' => 'Jakarta',
            ]
        );
    }
}
