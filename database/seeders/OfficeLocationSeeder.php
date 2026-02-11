<?php

namespace Database\Seeders;

use App\Models\OfficeLocation;
use Illuminate\Database\Seeder;

class OfficeLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices = [
            [
                'name' => 'Sekolah Pusat',
                'latitude' => -6.200000,
                'longitude' => 106.816666,
                'radius' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Sekolah Cabang Bandung',
                'latitude' => -6.914744,
                'longitude' => 107.609810,
                'radius' => 150,
                'is_active' => true,
            ],
        ];

        foreach ($offices as $office) {
            \App\Models\OfficeLocation::updateOrCreate(
                ['name' => $office['name']],
                $office
            );
        }
    }
}
