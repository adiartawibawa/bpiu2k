<?php

namespace App\Exports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;

class PostsExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Post::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Slug',
            'Content',
            'Status',
            'Category',
        ];
    }
}
