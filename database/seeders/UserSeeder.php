<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\user;
use Faker\Factory as Faker;

class userSeeder extends Seeder
{
    public function run()
    {
        // Use the factory to create 50 students with correct structure
        User::factory(50)->create([
            'group' => 'student'
        ]);
    }
}
