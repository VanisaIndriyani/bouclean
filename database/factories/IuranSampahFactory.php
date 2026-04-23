<?php

namespace Database\Factories;

use App\Models\IuranSampah;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IuranSampah>
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
            'warga_id' => Warga::factory(),
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
