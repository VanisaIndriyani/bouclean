<?php

namespace Database\Factories;

use App\Models\Wilayah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Wilayah>
 */
class WilayahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kecamatan' => $this->faker->citySuffix,
            'kelurahan' => $this->faker->streetName,
            'rt' => $this->faker->numerify('0##'),
            'rw' => $this->faker->numerify('0##'),
            'dasawisma' => 'Dahlia '.$this->faker->numerify('##'),
            'nama_pengguna' => $this->faker->name,
        ];
    }
}
