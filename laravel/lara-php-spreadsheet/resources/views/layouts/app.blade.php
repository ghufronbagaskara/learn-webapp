<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Aplikasi Data Penduduk' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="min-h-screen bg-base-200 text-base-content">
        <div class="navbar border-b border-base-300 bg-base-100 px-4 shadow-sm">
            <div class="navbar-start">
                <a href="{{ route('penduduk.index') }}" class="btn btn-ghost text-lg">Aplikasi Data Penduduk</a>
            </div>
            <div class="navbar-end gap-2">
                <a href="{{ route('penduduk.index') }}" class="btn btn-ghost btn-sm">Daftar Penduduk</a>
                <a href="{{ route('penduduk.create') }}" class="btn btn-primary btn-sm">Tambah Data</a>
            </div>
        </div>

        <main class="mx-auto w-full max-w-7xl px-4 py-6 md:px-6">
            @if (session('success'))
                <div class="alert alert-success mb-4">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error mb-4">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @isset($slot)
                {{ $slot }}
            @endisset

            @yield('content')
        </main>

        @livewireScripts
    </body>
</html>
