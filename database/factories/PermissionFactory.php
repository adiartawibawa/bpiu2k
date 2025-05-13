<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PermissionFactory extends Factory
{

    protected array $permissionMap = [
        'content' => [
            'create' => 'Create content',
            'read' => 'View content',
            'update' => 'Update content',
            'delete' => 'Delete content',
            'publish' => 'Publish content',
            'archive' => 'Archive content',
        ],
        'user' => [
            'create' => 'Create users',
            'read' => 'View users',
            'update' => 'Update users',
            'delete' => 'Delete users',
            'suspend' => 'Suspend users',
        ],
        'media' => [
            'upload' => 'Upload media',
            'manage' => 'Manage media',
            'delete' => 'Delete media',
        ],
        'settings' => [
            'read' => 'View settings',
            'update' => 'Update settings',
        ],
        'menu' => [
            'manage' => 'Manage menus',
        ],
        'role' => [
            'assign' => 'Assign roles',
            'manage' => 'Manage roles',
        ],
    ];

    public function definition(): array
    {
        $group = $this->faker->randomElement(array_keys($this->permissionMap));
        $action = $this->faker->randomElement(array_keys($this->permissionMap[$group]));

        return [
            'id' => Str::uuid(),
            'name' => "{$group}.{$action}",
            'description' => $this->permissionMap[$group][$action],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forPermission(string $group, string $action): static
    {
        return $this->state([
            'name' => "{$group}.{$action}",
            'description' => $this->permissionMap[$group][$action] ?? "{$action} {$group}",
        ]);
    }
}
