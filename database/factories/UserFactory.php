<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Factory de usuarios para pruebas y seeders de Laravel.
 *
 * Genera credenciales ficticias con contraseña hasheada reutilizable
 * entre instancias para optimizar la suite de tests.
 *
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    /**
     * Contraseña en caché compartida entre llamadas a la factory.
     */
    private static string $password;

    /**
     * Define el estado por defecto del modelo User.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => self::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Marca el usuario como no verificado (email_verified_at = null).
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }
}
