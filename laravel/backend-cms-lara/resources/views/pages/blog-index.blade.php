<x-app-layout :page-title="$pageTitle ?? 'Blog'">
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <header class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">Blog & Articles</h1>
            <p class="text-sm text-gray-500 mt-1">Latest insights from our team.</p>
        </header>

        <div class="space-y-4">
            @forelse($posts as $post)
                <article class="border border-gray-100 rounded-xl p-4" wire:key="blog-post-{{ $post->id }}">
                    <h2 class="text-xl font-semibold text-gray-900 tracking-tight">{{ $post->title }}</h2>
                    <p class="text-sm text-gray-500 mt-1">By {{ $post->author?->name }} • {{ optional($post->published_at)->format('M d, Y') }}</p>
                    <p class="text-sm text-gray-600 mt-3">{{ $post->excerpt }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="mt-3 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">Read article</a>
                </article>
            @empty
                <article class="text-center py-10 text-gray-400">No records found.</article>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </section>
</x-app-layout>
