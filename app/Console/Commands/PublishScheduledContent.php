<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Console\Command;

class PublishScheduledContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled posts and pages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Publish scheduled posts
        Post::where('status', 'draft')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->each(function ($post) {
                $post->update([
                    'status' => 'published',
                    'published_at' => now(),
                    'scheduled_at' => null,
                ]);
                $this->info("Published post: {$post->title}");
            });

        // Publish scheduled pages
        Page::where('status', 'draft')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->each(function ($page) {
                $page->update([
                    'status' => 'published',
                    'published_at' => now(),
                    'scheduled_at' => null,
                ]);
                $this->info("Published page: {$page->title}");
            });

        $this->info('Scheduled content publishing completed!');
    }
}
