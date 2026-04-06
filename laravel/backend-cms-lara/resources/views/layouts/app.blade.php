<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-seo-head :seo="$seo ?? null" :title="$pageTitle ?? null" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">
    <header class="border-b border-gray-200 bg-white">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-lg font-semibold tracking-tight text-gray-900">Maxian Corp CMS</a>
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Home</a>
                <a href="{{ route('blog.index') }}" class="hover:text-indigo-600 transition">Blog</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">Admin</a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition">Sign in</a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <x-flash-message />
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="border-t border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-500">
            &copy; {{ now()->year }} Maxian Corp. All rights reserved.
        </div>
    </footer>

    @livewireScripts
</body>
</html>
