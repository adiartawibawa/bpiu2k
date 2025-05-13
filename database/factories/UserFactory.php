<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
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
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'avatar' => null,
            'status' => fake()->randomElement(['active', 'inactive']),
            'remember_token' => Str::random(10),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            // Create avatar media for some users (70% chance)
            if (fake()->boolean(70)) {
                $user->media()->create([
                    'name' => 'avatar-' . $user->id,
                    'file_name' => 'avatar.jpg',
                    'mime_type' => 'image/jpeg',
                    'path' => 'avatars/' . $user->id . '.jpg',
                    'disk' => 'public',
                    'size' => fake()->numberBetween(100, 2000),
                    'user_id' => $user->id,
                    'collection_name' => 'avatar',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
