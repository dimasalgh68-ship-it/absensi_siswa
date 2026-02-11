<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Education>
 */
class EducationFactory extends Factory
{
    static $educations = ['Kelas 10', 'Kelas 11', 'Kelas 12', 'Kelas 10 IPA', 'Kelas 11 IPA', 'Kelas 12 IPA', 'Kelas 10 IPS', 'Kelas 11 IPS', 'Kelas 12 IPS'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(self::$educations),
        ];
    }
}
