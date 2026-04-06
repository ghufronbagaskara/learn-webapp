<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-seo-head :seo="$seo ?? null" :title="$pageTitle ?? 'Admin'" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://unpkg.com/survey-creator-core/survey-creator-core.min.css" rel="stylesheet">
    <script src="https://unpkg.com/survey-core/survey.core.min.js"></script>
    <script src="https://unpkg.com/survey-creator-core/survey-creator-core.min.js"></script>
    <script src="https://unpkg.com/survey-creator-knockout/survey-creator-knockout.min.js"></script>
</head>
<body class="min-h-screen bg-gray-100 text-gray-900 antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-[260px_1fr]">
        <aside class="bg-white border-r border-gray-200">
            <div class="px-6 py-5 border-b border-gray-100">
                <a href="{{ route('admin.dashboard') }}" class="text-lg font-semibold text-gray-900 tracking-tight">Admin Panel</a>
                <p class="text-sm text-gray-500">Corporate Web CMS</p>
            </div>
            <nav class="p-4 space-y-2">
                <a wire:navigate href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">Dashboard</a>
                <a wire:navigate href="{{ route('admin.pages.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.pages.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">Pages</a>
                <a wire:navigate href="{{ route('admin.posts.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.posts.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">Blog / Posts</a>
                <a wire:navigate href="{{ route('admin.surveys.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.surveys.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">Surveys</a>
                @if(auth()->user()?->role === 'admin')
                    <a wire:navigate href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">Users</a>
                @endif
            </nav>
            <div class="p-4 mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition">Sign out</button>
                </form>
            </div>
        </aside>

        <div>
            <header class="border-b border-gray-200 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                    <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">{{ $headerTitle ?? $pageTitle ?? 'Admin' }}</h1>
                    @isset($headerSubtitle)
                        <p class="text-sm text-gray-500 mt-1">{{ $headerSubtitle }}</p>
                    @endisset
                </div>
            </header>

            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <x-flash-message />
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
