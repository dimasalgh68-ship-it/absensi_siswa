<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample students
        $students = [
            [
                'nisn' => '24251001',
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@siswa.sch.id',
                'phone' => '081234560001',
                'gender' => 'male',
                'birth_date' => '2009-01-15',
                'birth_place' => 'Jakarta',
                'address' => 'Jl. Merdeka No. 1',
                'city' => 'Jakarta',
            ],
            [
                'nisn' => '24251002',
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@siswa.sch.id',
                'phone' => '081234560002',
                'gender' => 'female',
                'birth_date' => '2009-03-20',
                'birth_place' => 'Bandung',
                'address' => 'Jl. Kenanga No. 2',
                'city' => 'Bandung',
            ],
            [
                'nisn' => '24251003',
                'name' => 'Rizki Pratama',
                'email' => 'rizki.pratama@siswa.sch.id',
                'phone' => '081234560003',
                'gender' => 'male',
                'birth_date' => '2008-05-10',
                'birth_place' => 'Surabaya',
                'address' => 'Jl. Diponegoro No. 3',
                'city' => 'Surabaya',
            ],
            [
                'nisn' => '24251004',
                'name' => 'Sari Wulandari',
                'email' => 'sari.wulandari@siswa.sch.id',
                'phone' => '081234560004',
                'gender' => 'female',
                'birth_date' => '2009-07-25',
                'birth_place' => 'Yogyakarta',
                'address' => 'Jl. Malioboro No. 4',
                'city' => 'Yogyakarta',
            ],
            [
                'nisn' => '24251005',
                'name' => 'Budi Setiawan',
                'email' => 'budi.setiawan@siswa.sch.id',
                'phone' => '081234560005',
                'gender' => 'male',
                'birth_date' => '2008-09-12',
                'birth_place' => 'Semarang',
                'address' => 'Jl. Pandanaran No. 5',
                'city' => 'Semarang',
            ],
        ];

        foreach ($students as $student) {
            User::updateOrCreate(
                ['email' => $student['email']],
                array_merge($student, [
                    'password' => Hash::make('student123'),
                    'raw_password' => 'student123',
                    'group' => 'student',
                    'education_id' => 1,
                    'division_id' => 1,
                    'job_title_id' => 1,
                ])
            );
        }
    }
}
