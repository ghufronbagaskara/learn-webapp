@props(['post'])

@php
    $url = urlencode(route('blog.show', $post));
    $title = urlencode($post->title);
@endphp

<section class="rounded-xl border border-zinc-200 bg-white p-4 dark:bg-zinc-900">
    <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-zinc-500">Bagikan Artikel</h3>

    <div class="flex flex-wrap gap-2">
        <a href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $title }}" target="_blank" rel="noopener" class="rounded-lg border border-zinc-200 px-3 py-2 text-xs hover:bg-zinc-50">Twitter/X</a>
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank" rel="noopener" class="rounded-lg border border-zinc-200 px-3 py-2 text-xs hover:bg-zinc-50">Facebook</a>
        <a href="https://wa.me/?text={{ $title }}%20{{ $url }}" target="_blank" rel="noopener" class="rounded-lg border border-zinc-200 px-3 py-2 text-xs hover:bg-zinc-50">WhatsApp</a>
        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}" target="_blank" rel="noopener" class="rounded-lg border border-zinc-200 px-3 py-2 text-xs hover:bg-zinc-50">LinkedIn</a>
        <button type="button" onclick="navigator.clipboard.writeText('{{ route('blog.show', $post) }}')" class="rounded-lg border border-zinc-200 px-3 py-2 text-xs hover:bg-zinc-50">Copy Link</button>
    </div>
</section>
