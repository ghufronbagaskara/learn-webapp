<div class="relative">
    <input
        wire:model.live.debounce.300ms="query"
        type="text"
        placeholder="Cari artikel..."
        class="w-full rounded-lg border border-zinc-300 px-4 py-2 text-sm"
    />

    @if ($query !== '')
        <div class="absolute z-20 mt-2 w-full rounded-lg border border-zinc-200 bg-white shadow-lg">
            @forelse ($results as $result)
                <a href="{{ route('blog.show', $result) }}" class="block border-b border-zinc-100 px-4 py-3 text-sm hover:bg-zinc-50">
                    <p class="font-semibold">{{ $result->title }}</p>
                    <p class="mt-1 text-xs text-zinc-500">{{ $result->excerpt }}</p>
                </a>
            @empty
                <p class="px-4 py-3 text-sm text-zinc-500">Tidak ada hasil.</p>
            @endforelse
        </div>
    @endif
</div>
