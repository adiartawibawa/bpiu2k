<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->text(2000),
            'status' => fake()->randomElement(['draft', 'published']),
            'published_at' => fake()->dateTimeBetween('-1 year', '+1 month'),
            'author_id' => User::factory(),
            'meta_title' => fake()->sentence(),
            'meta_description' => fake()->paragraph(),
            'layout' => fake()->randomElement(['default', 'fullwidth', 'sidebar']),
            'order' => fake()->numberBetween(0, 100),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function withFeaturedImage(): static
    {
        return $this->afterCreating(function (Page $page) {
            $page->featuredImage()->create([
                'name' => 'featured-' . $page->id,
                'file_name' => 'featured.jpg',
                'mime_type' => 'image/jpeg',
                'path' => 'pages/' . $page->id . '/featured.jpg',
                'disk' => 'public',
                'size' => fake()->numberBetween(100, 2000),
                'user_id' => $page->author_id,
                'collection_name' => 'featured',
            ]);
        });
    }
}
