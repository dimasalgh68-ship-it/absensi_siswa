<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Education;
use App\Models\JobTitle;
use App\Models\Shift;
use App\Models\User;
use Database\Factories\DivisionFactory;
use Database\Factories\EducationFactory;
use Database\Factories\JobTitleFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (DivisionFactory::$divisions as $value) {
            if (Division::where('name', $value)->exists()) {
                continue;
            }
            Division::create(['name' => $value]);
        }
        
        foreach (EducationFactory::$educations as $value) {
            if (Education::where('name', $value)->exists()) {
                continue;
            }
            Education::create(['name' => $value]);
        }
        
        foreach (JobTitleFactory::$jobTitles as $value) {
            if (JobTitle::where('name', $value)->exists()) {
                continue;
            }
            JobTitle::create(['name' => $value]);
        }

        (new AdminSeeder)->run();
        (new TeacherSeeder)->run();
        (new StudentSeeder)->run();
        (new UserSeeder)->run();
        
        // Barcode seeding dihapus, diganti dengan Office Locations
        (new OfficeLocationSeeder)->run();
        
        if (Shift::count() === 0) {
            Shift::factory(2)->create();
        }
        
        // Seed default settings
        $this->seedDefaultSettings();
    }

    /**
     * Seed default application settings.
     */
    protected function seedDefaultSettings(): void
    {
        $defaultSettings = [
            'app_name' => config('app.name', 'Presensi Siswa'),
            'late_tolerance_minutes' => 15,
            'clock_out_early_minutes' => 30,
            'clock_in_early_minutes' => 60,  // Berapa menit sebelum shift siswa boleh absen masuk
            'clock_in_late_minutes' => 120,  // Berapa menit setelah shift dimulai siswa masih boleh absen masuk
            'face_similarity_threshold' => 70, // Persentase minimum kemiripan wajah (50-95)
        ];

        foreach ($defaultSettings as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
