<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Post;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;

class PostsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Post([
            'title' => $row['title'],
            'slug' => Str::slug($row['title']),
            'content' => $row['content'],
            'status' => $row['status'] ?? 'draft',
            'category_id' => Category::firstOrCreate(['name' => $row['category']])->id,
        ]);
    }
}
