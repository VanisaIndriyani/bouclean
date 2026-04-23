<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IuranSampah>
 */
class IuranSampahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'warga_id' => \App\Models\Warga::factory(),
            'user_id' => 1,
            'bulan' => $this->faker->monthName,
            'tahun' => date('Y'),
            'nominal' => $this->faker->randomElement([5000, 10000, 15000, 20000]),
            'status' => $this->faker->randomElement(['lunas', 'belum']),
            'tanggal_bayar' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'petugas' => $this->faker->name,
        ];
    }
}
