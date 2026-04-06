<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-seo-head :seo="$seo ?? null" :title="$pageTitle ?? 'Authentication'" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-linear-to-b from-slate-100 via-white to-slate-100">
    <main class="min-h-screen flex items-center justify-center px-4 py-10">
        <section class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            {{ $slot }}
            @yield('content')
        </section>
    </main>
</body>
</html>
