<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SolutionForest\FilamentTree\Concern\ModelTree;

class MenuItem extends Model
{
    use HasFactory, ModelTree;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'order',
        'title',
        'url',
        'target',
        'icon',
        'type',
        'route',
        'parameters'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // public function parent()
    // {
    //     return $this->belongsTo(MenuItem::class, 'parent_id');
    // }

    // public function children()
    // {
    //     return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    // }
}
