@once
    <link rel="stylesheet" href="https://unpkg.com/trix@2.1.12/dist/trix.css">
    <script src="https://unpkg.com/trix@2.1.12/dist/trix.umd.min.js"></script>
@endonce

<form wire:submit="save" class="space-y-6">
    <div>
        <label class="mb-2 block text-sm font-medium">Judul</label>
        <input wire:model="title" type="text" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-medium">Kategori</label>
            <select wire:model="category_id" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">Status</label>
            <select wire:model="status" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
                @foreach ($statuses as $statusValue)
                    <option value="{{ $statusValue }}">{{ ucfirst($statusValue) }}</option>
                @endforeach
            </select>
            @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium">Konten</label>
        <div wire:ignore>
            <input id="trix-content-{{ $this->getId() }}" type="hidden" value="{{ $content }}">
            <trix-editor
                input="trix-content-{{ $this->getId() }}"
                class="min-h-64 rounded-lg border border-zinc-300 bg-white"
                x-data
                x-on:trix-change="$wire.set('content', $event.target.value)"
            ></trix-editor>
        </div>
        @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium">Tag</label>
        <select wire:model="tag_ids" multiple class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
            @foreach ($tags as $tag)
                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium">Excerpt</label>
        <textarea wire:model="excerpt" rows="3" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm"></textarea>
        @error('excerpt') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-medium">Meta Title</label>
            <input wire:model="meta_title" type="text" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium">Meta Keywords</label>
            <input wire:model="meta_keywords" type="text" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium">Meta Description</label>
        <textarea wire:model="meta_description" rows="3" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm"></textarea>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-medium">OG Image URL</label>
            <input wire:model="og_image" type="url" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium">Canonical URL</label>
            <input wire:model="canonical_url" type="url" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium">Featured Image</label>
        <input wire:model="featured_image" type="file" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
        @error('featured_image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="rounded-xl border border-zinc-200 p-4">
        <h3 class="mb-2 text-sm font-semibold uppercase tracking-wide text-zinc-500">Live Preview</h3>
        <h2 class="text-2xl font-bold">{{ $title ?: 'Judul artikel...' }}</h2>
        <div class="prose mt-3 max-w-none text-sm text-zinc-700">{!! $content ?: '<p>Tulis konten artikel untuk melihat preview.</p>' !!}</div>
    </div>

    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-500">
        {{ $post ? 'Perbarui Artikel' : 'Simpan Artikel' }}
    </button>
</form>
