<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - {{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-5">
            <div>
                <h1 class="font-semibold text-slate-800 tracking-tight text-2xl">Sign In</h1>
                <p class="text-sm text-slate-600">Masuk untuk mengakses dashboard reporting.</p>
            </div>

            <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                @csrf

                <div class="flex flex-col gap-1">
                    <label for="email" class="text-xs font-medium text-slate-500 uppercase tracking-wide">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required class="rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white" />
                    @error('email') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label for="password" class="text-xs font-medium text-slate-500 uppercase tracking-wide">Password</label>
                    <input id="password" name="password" type="password" required class="rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white" />
                    @error('password') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                    Remember me
                </label>

                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                    Login
                </button>
            </form>

            <p class="text-sm text-slate-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-700">Register</a>
            </p>
        </div>
    </body>
</html>
