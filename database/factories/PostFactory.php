<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = fake()->sentence();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->text(2000),
            'status' => fake()->randomElement(['draft', 'published', 'archived']),
            'published_at' => fake()->dateTimeBetween('-1 year', '+1 month'),
            'author_id' => User::factory(),
            'category_id' => null,
            'meta_title' => fake()->sentence(),
            'meta_description' => fake()->paragraph(),
            'is_featured' => fake()->boolean(20),
        ];
    }

    // Factory state methods
    public function withCategory()
    {
        return $this->afterCreating(function (Post $post) {
            $post->category()->associate(Category::factory()->create());
            $post->save();
        });
    }

    public function withTags($count = 3)
    {
        return $this->afterCreating(function (Post $post) use ($count) {
            $tags = Tag::factory()->count($count)->create();
            $post->tags()->attach($tags);
        });
    }

    public function withFeaturedImage()
    {
        return $this->afterCreating(function (Post $post) {
            $post->media()->create([
                'name' => 'featured-' . $post->id,
                'file_name' => 'featured.jpg',
                'mime_type' => 'image/jpeg',
                'path' => 'posts/' . $post->id . '/featured.jpg',
                'disk' => 'public',
                'size' => fake()->numberBetween(100, 2000),
                'user_id' => $post->author_id,
                'collection_name' => 'featured',
            ]);
        });
    }

    public function withGallery($count = 5)
    {
        return $this->afterCreating(function (Post $post) use ($count) {
            foreach (range(1, $count) as $i) {
                $post->media()->create([
                    'name' => 'gallery-' . $post->id . '-' . $i,
                    'file_name' => 'gallery-' . $i . '.jpg',
                    'mime_type' => 'image/jpeg',
                    'path' => 'posts/' . $post->id . '/gallery-' . $i . '.jpg',
                    'disk' => 'public',
                    'size' => fake()->numberBetween(100, 2000),
                    'user_id' => $post->author_id,
                    'collection_name' => 'gallery',
                    'order_column' => $i,
                ]);
            }
        });
    }
}
