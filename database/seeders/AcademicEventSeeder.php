<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class AcademicEventSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch holidays from API for current year
        $this->command->info('Fetching holidays from API...');
        Artisan::call('holidays:fetch', ['year' => now()->year]);
        $this->command->info(Artisan::output());
        
        // You can also fetch for next year
        Artisan::call('holidays:fetch', ['year' => now()->year + 1]);
        $this->command->info(Artisan::output());
    }
}
