<?php

namespace App\Observers;

use App\Models\Page;

class PageObserver
{
    public function restored(Page $page)
    {
        activity()
            ->performedOn($page)
            ->causedBy(auth()->user())
            ->log('restored');
    }

    public function forceDeleted(Page $page)
    {
        activity()
            ->performedOn($page)
            ->causedBy(auth()->user())
            ->log('forceDeleted');
    }
}
