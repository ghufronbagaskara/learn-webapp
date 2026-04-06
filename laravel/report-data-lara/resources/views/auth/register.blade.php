<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register - {{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-5">
            <div>
                <h1 class="font-semibold text-slate-800 tracking-tight text-2xl">Create Account</h1>
                <p class="text-sm text-slate-600">Daftar untuk mulai menggunakan reporting dashboard.</p>
            </div>

            <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                @csrf

                <div class="flex flex-col gap-1">
                    <label for="name" class="text-xs font-medium text-slate-500 uppercase tracking-wide">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required class="rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white" />
                    @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

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

                <div class="flex flex-col gap-1">
                    <label for="password_confirmation" class="text-xs font-medium text-slate-500 uppercase tracking-wide">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white" />
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                    Register
                </button>
            </form>

            <p class="text-sm text-slate-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-700">Login</a>
            </p>
        </div>
    </body>
</html>
