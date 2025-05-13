@extends('layouts.preview')

@section('content')
    <article class="max-w-4xl py-8 mx-auto">
        @if (!$model->isPublished())
            <div class="p-4 mb-6 bg-yellow-100 border-l-4 border-yellow-500">
                <p class="text-yellow-700">⚠️ Ini adalah preview draft. Konten belum dipublikasikan.</p>
            </div>
        @endif

        <h1 class="mb-4 text-4xl font-bold">{{ $model->title }}</h1>

        <div class="prose max-w-none">
            {!! $model->content !!}
        </div>
    </article>
@endsection
