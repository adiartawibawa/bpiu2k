<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'name',
        'file_name',
        'mime_type',
        'path',
        'disk',
        'size',
        'user_id',
        'model_type',
        'model_id',
        'collection_name',
        'custom_properties',
        'order_column'
    ];

    protected $casts = [
        'custom_properties' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function model()
    {
        return $this->morphTo();
    }

    // Helper untuk mendapatkan URL media
    public function getUrl()
    {
        return storage_path('app/' . $this->path);
    }

    // Scope untuk gambar featured
    public function scopeFeatured($query)
    {
        return $query->where('collection_name', 'featured');
    }

    // Scope untuk gallery
    public function scopeGallery($query)
    {
        return $query->where('collection_name', 'gallery');
    }
}
