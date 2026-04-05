<x-layouts::auth :title="__('Two-factor challenge')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Verifikasi 2FA')" :description="__('Masukkan kode dari aplikasi authenticator Anda.')" />

        <form method="POST" action="{{ route('2fa.verify') }}" class="space-y-4">
            @csrf
            <flux:input
                name="code"
                :label="__('Kode OTP')"
                type="text"
                required
                autofocus
                :value="old('code')"
                placeholder="123456"
            />

            @error('code')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Verifikasi') }}
            </flux:button>
        </form>
    </div>
</x-layouts::auth>
