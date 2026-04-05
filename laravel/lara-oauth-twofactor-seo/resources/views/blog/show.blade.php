<x-layouts::app :title="$post->title">
    <article class="mx-auto w-full max-w-4xl space-y-8">
        <header class="space-y-3">
            <p class="text-xs uppercase tracking-wider text-zinc-500">{{ $post->category->name }}</p>
            <h1 class="text-4xl font-bold leading-tight">{{ $post->title }}</h1>
            <p class="text-sm text-zinc-500">
                {{ $post->user->name }} &bull; {{ optional($post->published_at)->format('d M Y H:i') }}
            </p>
        </header>

        @if ($post->featured_image)
            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="h-auto w-full rounded-2xl border border-zinc-200 object-cover" />
        @endif

        <div class="prose max-w-none rounded-xl border border-zinc-200 bg-white p-6 dark:bg-zinc-900">
            {!! $post->content !!}
        </div>

        <x-social-share :post="$post" />

        <livewire:comment-section :post="$post" />
    </article>
</x-layouts::app>
