    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800"></body>
    <flux:main>
        {{ $slot }}
    </flux:main>
    </html>
