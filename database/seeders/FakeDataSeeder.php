<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FakeDataSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder utama
        $this->call(DatabaseSeeder::class);

        // Generate 50 user random
        User::factory(50)->create();

        // Generate 1 user khusus untuk testing
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'raw_password' => 'password',
                'group' => 'student',
                'phone' => '081234567899',
                'gender' => 'male',
                'address' => 'Jakarta',
                'city' => 'Jakarta',
                'nisn' => '1234567890',
            ]
        );

        // Jalankan AttendanceSeeder
        $this->call(AttendanceSeeder::class);
    }
}
