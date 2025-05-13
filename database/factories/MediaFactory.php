<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $modelTypes = ['App\Models\Post', 'App\Models\Page', 'App\Models\User'];
        $collections = ['default', 'featured', 'gallery', 'avatar'];

        return [
            'name' => fake()->word(),
            'file_name' => fake()->word() . '.jpg',
            'mime_type' => 'image/jpeg',
            'path' => 'media/' . fake()->word() . '.jpg',
            'disk' => 'public',
            'size' => fake()->numberBetween(100, 2000),
            'user_id' => User::factory(),
            'model_type' => fake()->randomElement($modelTypes),
            'model_id' => Str::uuid(),
            'collection_name' => fake()->randomElement($collections),
            'custom_properties' => [],
            'order_column' => fake()->numberBetween(1, 100),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
