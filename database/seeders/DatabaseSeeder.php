<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Permission;
use App\Models\Post;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // $this->seedRolesAndPermissions();
            $this->seedUsers();
            $this->seedContent();
            $this->seedMenus();
        });
    }

    protected function seedRolesAndPermissions(): void
    {
        // Create roles
        $roles = [
            'admin'       => Role::firstOrCreate(['name' => 'admin'], [
                'description' => 'Administrator with full system access'
            ]),
            'editor'      => Role::firstOrCreate(['name' => 'editor'], [
                'description' => 'Can create and edit content'
            ]),
            'author'      => Role::firstOrCreate(['name' => 'author'], [
                'description' => 'Can create and manage own content'
            ]),
            'contributor' => Role::firstOrCreate(['name' => 'contributor'], [
                'description' => 'Can submit content for review'
            ]),
            'subscriber'  => Role::firstOrCreate(['name' => 'subscriber'], [
                'description' => 'Basic read-only access'
            ]),
        ];

        // Create permissions
        $permissions = [
            'content.create'  => Permission::firstOrCreate(['name' => 'content.create'], ['description' => 'Create content']),
            'content.read'    => Permission::firstOrCreate(['name' => 'content.read'], ['description' => 'View content']),
            'content.update'  => Permission::firstOrCreate(['name' => 'content.update'], ['description' => 'Update content']),
            'content.delete'  => Permission::firstOrCreate(['name' => 'content.delete'], ['description' => 'Delete content']),
            'content.publish' => Permission::firstOrCreate(['name' => 'content.publish'], ['description' => 'Publish content']),
            'media.upload'   => Permission::firstOrCreate(['name' => 'media.upload'], ['description' => 'Upload media']),
        ];

        // Assign permissions to roles
        $roles['admin']->permissions()->sync($permissions);
        $roles['editor']->permissions()->sync([
            $permissions['content.create']->id,
            $permissions['content.read']->id,
            $permissions['content.update']->id,
            $permissions['content.publish']->id,
            $permissions['media.upload']->id,
        ]);
        $roles['author']->permissions()->sync([
            $permissions['content.create']->id,
            $permissions['content.read']->id,
            $permissions['content.update']->id,
            $permissions['media.upload']->id,
        ]);
    }

    protected function seedUsers(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(['email' => 'admin@example.com'], [
            'name'     => 'Admin User',
            'password' => Hash::make('password'),
            'status'   => 'active',
        ]);
        // $admin->roles()->sync([Role::where('name', 'admin')->first()->id]);

        // Create editor user
        $editor = User::firstOrCreate(['email' => 'editor@example.com'], [
            'name'     => 'Editor User',
            'password' => Hash::make('password'),
            'status'   => 'active',
        ]);
        // $editor->roles()->sync([Role::where('name', 'editor')->first()->id]);

        // Create regular users
        User::factory(10)->create();
        // User::factory(10)->create()->each(function ($user) {
        //     $roles = Role::where('name', '!=', 'admin')
        //         ->inRandomOrder()
        //         ->limit(rand(1, 2))
        //         ->pluck('id');
        //     $user->roles()->sync($roles);
        // });
    }

    protected function seedContent(): void
    {
        // Create categories
        $categories = Category::factory(5)->create();
        $categories->each(function ($category) {
            if (fake()->boolean(40)) {
                Category::factory(fake()->numberBetween(1, 3))
                    ->create(['parent_id' => $category->id]);
            }
        });

        // Create tags
        $tags = Tag::factory(15)->create();

        // Create posts
        Post::factory(15)
            ->withCategory()
            ->hasAttached(
                $tags->random(rand(1, 5)),
                [],
                'tags'
            )
            ->create();

        // Create pages
        Page::factory(8)->create();
    }

    protected function seedMenus(): void
    {
        // Main menu
        $mainMenu = Menu::firstOrCreate(
            ['name' => 'Main Navigation'],
            ['location' => 'header']
        );

        $mainItems = MenuItem::factory(5)
            ->for($mainMenu)
            ->create();

        $mainItems->each(function ($item) use ($mainMenu) {
            if (fake()->boolean(30)) {
                MenuItem::factory(fake()->numberBetween(1, 3))
                    ->for($mainMenu)
                    ->create(['parent_id' => $item->id]);
            }
        });

        // Footer menu
        $footerMenu = Menu::firstOrCreate(
            ['name' => 'Footer Links'],
            ['location' => 'footer']
        );

        $footerMenu->items()->createMany([
            ['title' => 'Privacy Policy', 'url' => '/privacy', 'order' => 1],
            ['title' => 'Terms of Service', 'url' => '/terms', 'order' => 2],
            ['title' => 'Contact Us', 'url' => '/contact', 'order' => 3],
        ]);
    }
}
