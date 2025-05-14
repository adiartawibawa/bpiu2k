<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Page extends Model
{
    use HasFactory, HasUuids, SoftDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'published_at',
        'scheduled_at',
        'author_id',
        'meta_title',
        'meta_description',
        'layout',
        'order'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'scheduled_at' => 'datetime',
        ];
    }

    // Scope untuk status
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function canBePreviewed(): bool
    {
        return $this->author_id === auth()->id() ||
            auth()->user()->can('view unpublished pages');
    }

    // Relations
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function featuredImage()
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', 'featured');
    }

    // Helper methods
    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at <= now();
    }

    public function getLayoutName(): string
    {
        return $this->layout ?? 'default';
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'draft')
            ->whereNotNull('scheduled_at');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'slug', 'status', 'published_at', 'layout', 'order'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Page has been {$eventName}");
    }
}
