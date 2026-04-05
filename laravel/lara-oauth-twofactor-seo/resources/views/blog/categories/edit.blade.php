<x-layouts::app :title="__('Edit Kategori')">
    <div class="mx-auto max-w-xl space-y-4 rounded-xl border border-zinc-200 bg-white p-6 dark:bg-zinc-900">
        <h1 class="text-xl font-bold">Edit Kategori</h1>

        <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-medium">Nama</label>
                <input name="name" value="{{ old('name', $category->name) }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">Deskripsi</label>
                <textarea name="description" rows="4" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">{{ old('description', $category->description) }}</textarea>
            </div>

            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Simpan Perubahan</button>
        </form>

        <form method="POST" action="{{ route('categories.destroy', $category) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white">Hapus Kategori</button>
        </form>
    </div>
</x-layouts::app>
