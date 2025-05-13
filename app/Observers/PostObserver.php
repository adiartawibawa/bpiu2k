<?php

namespace App\Observers;

use App\Models\Post;

class PostObserver
{
    public function restored(Post $post)
    {
        activity()
            ->performedOn($post)
            ->causedBy(auth()->user())
            ->log('restored');
    }

    public function forceDeleted(Post $post)
    {
        activity()
            ->performedOn($post)
            ->causedBy(auth()->user())
            ->log('forceDeleted');
    }
}
