<div class="card mx-auto max-w-3xl bg-base-100 shadow">
    <div class="card-body">
        <h1 class="card-title text-2xl">Tambah Data Penduduk</h1>

        <form wire:submit="store" class="space-y-4">
            <div>
                <label class="label">
                    <span class="label-text">Nama</span>
                </label>
                <input type="text" wire:model="nama" class="input input-bordered w-full" placeholder="Masukkan nama lengkap">
                @error('nama') <p class="mt-1 text-sm text-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">
                    <span class="label-text">Usia</span>
                </label>
                <input type="number" wire:model="usia" class="input input-bordered w-full" placeholder="Masukkan usia">
                @error('usia') <p class="mt-1 text-sm text-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">
                    <span class="label-text">Alamat</span>
                </label>
                <textarea wire:model="alamat" rows="3" class="textarea textarea-bordered w-full" placeholder="Masukkan alamat lengkap"></textarea>
                @error('alamat') <p class="mt-1 text-sm text-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">
                    <span class="label-text">Pekerjaan</span>
                </label>
                <input type="text" wire:model="pekerjaan" class="input input-bordered w-full" placeholder="Masukkan pekerjaan">
                @error('pekerjaan') <p class="mt-1 text-sm text-error">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-wrap justify-end gap-2">
                <a href="{{ route('penduduk.index') }}" class="btn btn-ghost">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
