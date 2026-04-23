<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Perpindahan>
 */
class PerpindahanFactory extends Factory
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
            'asal' => $this->faker->address,
            'tujuan' => $this->faker->address,
            'diusulkan_oleh' => $this->faker->name,
            'status' => $this->faker->randomElement(['pending', 'disetujui', 'ditolak']),
            'tindak_lanjut' => $this->faker->sentence,
        ];
    }
}
