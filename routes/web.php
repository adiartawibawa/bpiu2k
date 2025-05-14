<?php

use App\Models\Page;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/preview/posts/{post}', function (Post $post) {
    // abort_unless(
    //     auth()->user() && $post->canBePreviewed(),
    //     403,
    //     'Preview tidak tersedia'
    // );

    return view('preview.post', ['model' => $post]);
})->name('posts.preview');

Route::get('/preview/pages/{page}', function (Page $page) {
    // abort_unless(
    //     auth()->user() && $page->canBePreviewed(),
    //     403,
    //     'Preview tidak tersedia'
    // );

    return view('preview.page', ['model' => $page]);
})->name('pages.preview');
