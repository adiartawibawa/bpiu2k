<!DOCTYPE html>
<html>

<head>
    <title>Preview: {{ $model->title ?? 'Untitled' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .preview-bar {
            background-color: #f3f4f6;
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }
    </style>
</head>

<body>
    @if (app()->environment('local'))
        <div class="preview-bar">
            <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline">
                ← Kembali ke Admin
            </a>
        </div>
    @endif

    @yield('content')
</body>

</html>
