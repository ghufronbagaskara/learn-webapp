<x-app-layout :seo="$seo ?? null" :page-title="$pageTitle">
    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <header class="mb-6">
            <p class="text-sm text-gray-500">{{ optional($post->published_at)->format('M d, Y') }} • {{ $post->author?->name }}</p>
            <h1 class="text-2xl font-semibold text-gray-900 tracking-tight mt-1">{{ $post->title }}</h1>
            <p class="text-base text-gray-700 mt-4">{{ $post->excerpt }}</p>
        </header>

        @if($post->cover_image)
            <img src="{{ asset('storage/'.$post->cover_image) }}" alt="{{ $post->title }}" class="w-full max-h-96 object-cover rounded-xl border border-gray-100 mb-6">
        @endif

        <section class="prose prose-sm max-w-none text-gray-700">
            {!! $post->body !!}
        </section>
    </article>
</x-app-layout>
