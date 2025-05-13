<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Tag extends Model
{
    use HasUuids, HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    // Scope untuk pencarian
    public function scopeSearch($query, string $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('slug', 'like', "%{$search}%");
    }

    // Helper method
    public function getPostsCountAttribute(): int
    {
        return $this->posts()->count();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Tag has been {$eventName}");
    }
}
