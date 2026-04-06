<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-700 antialiased">
        @auth
            <header class="bg-white border-b border-slate-200 px-6 py-4">
                <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Reporting Dashboard</p>
                        <h1 class="font-semibold text-slate-800 tracking-tight">{{ config('app.name') }}</h1>
                    </div>

                    <div class="inline-flex items-center gap-2">
                        <a href="{{ route('reports.sales') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                            Sales
                        </a>
                        <a href="{{ route('reports.pivot') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                            Pivot
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>
        @endauth

        {{ $slot }}

        @livewireScripts
        @stack('scripts')
    </body>
</html>
