<?php

namespace Database\Factories;

use App\Models\Warga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Warga>
 */
class WargaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'nama_lengkap' => $this->faker->name,
            'nik' => $this->faker->unique()->numerify('################'),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'tempat_lahir' => $this->faker->city,
            'tanggal_lahir' => $this->faker->date('Y-m-d', '-20 years'),
            'kecamatan' => $this->faker->citySuffix,
            'kelurahan' => $this->faker->streetName,
            'rt' => $this->faker->numerify('0##'),
            'rw' => $this->faker->numerify('0##'),
            'dasawisma' => 'Dahlia '.$this->faker->numerify('##'),
        ];
    }
}
