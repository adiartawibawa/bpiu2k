<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
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
            $this->seedUsers();
            $this->seedContent();
            $this->seedMenus();

            // Artisan::call('shield:generate --all');

            // // Menjalankan command shield:super-admin
            // Artisan::call('shield:super-admin', [
            //     '--user' => 'Admin User',
            //     '--panel' => 'admin'
            // ]);
        });
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
