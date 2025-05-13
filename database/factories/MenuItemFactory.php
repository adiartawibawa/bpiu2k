<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuItem>
 */
class MenuItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_id' => Menu::factory(),
            'parent_id' => null,
            'title' => $this->faker->word(),
            'url' => $this->faker->url(),
            'target' => $this->faker->randomElement(['_self', '_blank']),
            'icon' => $this->faker->randomElement(['home', 'user', 'settings', 'info', null]),
            'order' => $this->faker->numberBetween(1, 100),
            'type' => $this->faker->randomElement(['link', 'page', 'post']),
            'route' => $this->faker->randomElement(['home', 'about', 'contact', null]),
            'parameters' => null,
        ];
    }

    public function withChildren($count = 3)
    {
        return $this->afterCreating(function (MenuItem $menuItem) use ($count) {
            MenuItem::factory()
                ->count($count)
                ->create([
                    'menu_id' => $menuItem->menu_id,
                    'parent_id' => $menuItem->id,
                    'order' => $this->faker->numberBetween(1, 100),
                ]);
        });
    }
}
