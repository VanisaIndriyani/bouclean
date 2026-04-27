<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $username = fake()->unique()->userName();
        $username = strtolower(trim((string) $username));
        $username = preg_replace('/\s+/', '', $username) ?? $username;
        $username = preg_replace('/[^a-z0-9_.]/', '', $username) ?? $username;
        $username = substr($username, 0, 30);
        if ($username === '') {
            $username = 'user'.fake()->unique()->numberBetween(1000, 999999);
        }

        return [
            'name' => fake()->name(),
            'username' => $username,
            'email' => $username.'@bouclear.invalid',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'user',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
