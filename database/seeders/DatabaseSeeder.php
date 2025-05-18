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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->setupShield();
            $this->seedUsers();
            $this->seedContent();
            $this->seedMenus();
        });
    }

    protected function setupShield(): void
    {
        // Jalankan shield install
        // Artisan::call('shield:install');

        // Generate permissions
        // Artisan::call('shield:generate', ['--all' => true]);

        // Tambahkan role
        $adminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Berikan beberapa permission ke role admin
        $adminPermissions = [
            'view_user',
            'create_user',
            'edit_user',
            'delete_user',
            'view_post',
            'create_post',
            'edit_post',
            'delete_post',
            'view_page',
            'create_page',
            'edit_page',
            'delete_page',
            'view_category',
            'create_category',
            'edit_category',
            'delete_category',
            'view_tag',
            'create_tag',
            'edit_tag',
            'delete_tag',
            'view_menu',
            'create_menu',
            'edit_menu',
            'delete_menu',
        ];

        foreach ($adminPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $adminRole->givePermissionTo($permission);
        }

        // Berikan permission terbatas ke role user
        $userPermissions = [
            'view_post',
            'view_page',
            'view_category',
            'view_tag'
        ];

        foreach ($userPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $userRole->givePermissionTo($permission);
        }
    }

    protected function seedUsers(): void
    {
        // Create super admin user
        $superAdmin = User::firstOrCreate(['email' => 'superadmin@example.com'], [
            'name'     => 'Super Admin',
            'password' => Hash::make('password'),
            'status'   => 'active',
        ]);
        $superAdmin->assignRole('super_admin');

        // Create admin user
        $admin = User::firstOrCreate(['email' => 'admin@example.com'], [
            'name'     => 'Admin User',
            'password' => Hash::make('password'),
            'status'   => 'active',
        ]);
        $admin->assignRole('admin');

        // Create editor user (dengan role admin juga)
        $editor = User::firstOrCreate(['email' => 'editor@example.com'], [
            'name'     => 'Editor User',
            'password' => Hash::make('password'),
            'status'   => 'active',
        ]);
        $editor->assignRole('admin');

        // Create regular users
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('user');
        });
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
