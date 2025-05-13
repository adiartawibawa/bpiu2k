<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{
    public function restored(Category $category)
    {
        activity()
            ->performedOn($category)
            ->causedBy(auth()->user())
            ->log('restored');
    }

    public function forceDeleted(Category $category)
    {
        activity()
            ->performedOn($category)
            ->causedBy(auth()->user())
            ->log('forceDeleted');
    }
}
