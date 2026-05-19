<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample teachers
        $teacher1 = User::updateOrCreate(
            ['email' => 'guru1@example.com'],
            [
                'nisn' => 'GURU001',
                'name' => 'Budi Santoso',
                'phone' => '081234567890',
                'gender' => 'male',
                'birth_date' => '1985-05-15',
                'birth_place' => 'Jakarta',
                'address' => 'Jl. Pendidikan No. 123',
                'city' => 'Jakarta',
                'password' => Hash::make('guru123'),
                'raw_password' => 'guru123',
                'group' => 'teacher',
                'education_id' => 1,
                'division_id' => 1,
                'job_title_id' => 1,
            ]
        );

        // Create Teacher record for guru1
        Teacher::updateOrCreate(
            ['user_id' => $teacher1->id],
            [
                'nip' => 'NIP001',
                'specialization' => 'Matematika',
                'certification_number' => 'CERT001',
                'certification_date' => '2020-01-15',
                'status' => 'active',
            ]
        );

        $teacher2 = User::updateOrCreate(
            ['email' => 'guru2@example.com'],
            [
                'nisn' => 'GURU002',
                'name' => 'Siti Nurhaliza',
                'phone' => '081234567891',
                'gender' => 'female',
                'birth_date' => '1987-08-20',
                'birth_place' => 'Bandung',
                'address' => 'Jl. Guru Raya No. 45',
                'city' => 'Bandung',
                'password' => Hash::make('guru123'),
                'raw_password' => 'guru123',
                'group' => 'teacher',
                'education_id' => 1,
                'division_id' => 1,
                'job_title_id' => 1,
            ]
        );

        // Create Teacher record for guru2
        Teacher::updateOrCreate(
            ['user_id' => $teacher2->id],
            [
                'nip' => 'NIP002',
                'specialization' => 'Bahasa Indonesia',
                'certification_number' => 'CERT002',
                'certification_date' => '2019-06-20',
                'status' => 'active',
            ]
        );
    }
}
