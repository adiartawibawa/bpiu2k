<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Post extends Model
{
    use HasFactory, HasUuids, SoftDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'published_at',
        'author_id',
        'category_id',
        'meta_title',
        'meta_description',
        'is_featured'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean'
    ];

    // Scope untuk status
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    // public function canBePreviewed(): bool
    // {
    //     return $this->author_id === auth()->id() ||
    //         auth()->user()->can('content.preview');
    // }

    // Relations
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
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

    public function gallery()
    {
        return $this->morphMany(Media::class, 'model')
            ->where('collection_name', 'gallery');
    }

    // Helper methods
    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at <= now();
    }

    public function getReadingTime(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));
        return ceil($wordCount / 200); // Asumsi 200 kata per menit
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'slug', 'status', 'published_at', 'category_id', 'is_featured'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->logExcept(['updated_at', 'created_at'])
            ->setDescriptionForEvent(fn(string $eventName) => "Post has been {$eventName}");

        // Untuk logging semua atribut kecuali beberapa:
        // return LogOptions::defaults()
        //     ->logAll()
        //     ->logExcept(['updated_at', 'created_at']);
    }
}
