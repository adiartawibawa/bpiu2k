<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    protected array $roles = [
        'admin' => 'Administrator with full system access',
        'editor' => 'Can create and edit content',
        'author' => 'Can create and manage own content',
        'contributor' => 'Can submit content for review',
        'subscriber' => 'Basic read-only access',
        'manager' => 'Can manage users and settings',
        'moderator' => 'Can moderate user content',
    ];

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->unique()->randomElement(array_keys($this->roles)),
            'description' => fn(array $attrs) => $this->roles[$attrs['name']],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forRole(string $role): static
    {
        return $this->state([
            'name' => $role,
            'description' => $this->roles[$role],
        ]);
    }
}
