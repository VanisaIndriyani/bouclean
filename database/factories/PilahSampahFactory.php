<?php

namespace Database\Factories;

use App\Models\PilahSampah;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PilahSampah>
 */
class PilahSampahFactory extends Factory
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
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'berat' => $this->faker->numberBetween(100, 5000),
            'sedekah' => $this->faker->boolean,
            'harga' => $this->faker->numberBetween(1000, 50000),
            'foto' => null,
        ];
    }
}
