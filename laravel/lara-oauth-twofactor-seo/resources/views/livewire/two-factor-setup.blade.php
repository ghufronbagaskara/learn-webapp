<section class="space-y-4 rounded-xl border border-zinc-200 p-4">
    <h3 class="text-base font-semibold">Two-Factor Authentication (TOTP)</h3>
    <p class="text-sm text-zinc-600">
        Kode OTP 2FA berasal dari aplikasi authenticator (Google Authenticator, Microsoft Authenticator, Authy), bukan dari email.
    </p>

    @if (session('status'))
        <p class="rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">{{ session('status') }}</p>
    @endif

    @if ($isEnabled)
        <p class="text-sm text-zinc-600">2FA saat ini aktif untuk akun Anda.</p>
        <button wire:click="disable" type="button" class="rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-500">
            Nonaktifkan 2FA
        </button>
    @else
        <p class="text-sm text-zinc-600">Aktifkan 2FA untuk menambah keamanan akun Anda.</p>

        @if ($qrCodeInline)
            <div class="rounded-lg border border-zinc-200 bg-white p-3">
                @if (str_starts_with($qrCodeInline, 'data:image'))
                    <img src="{{ $qrCodeInline }}" alt="QR Code 2FA" class="mx-auto" />
                @else
                    {!! $qrCodeInline !!}
                @endif
            </div>

            @if ($manualSecret)
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800">
                    Jika QR tidak tampil atau tidak bisa discan, masukkan secret ini secara manual di aplikasi authenticator:
                    <div class="mt-1 break-all rounded bg-white px-2 py-1 font-mono text-xs text-zinc-800">{{ $manualSecret }}</div>
                </div>
            @endif

            @if ($otpauthUrl)
                <p class="text-xs text-zinc-500 break-all">OTP URI: {{ $otpauthUrl }}</p>
            @endif

            <div>
                <label class="mb-2 block text-sm font-medium">Masukkan kode OTP</label>
                <input wire:model="code" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="8" placeholder="123456" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button wire:click="confirm" type="button" class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                Konfirmasi 2FA
            </button>
        @else
            <button wire:click="setup" type="button" class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                Mulai Setup 2FA (Generate Secret)
            </button>
        @endif
    @endif
</section>
