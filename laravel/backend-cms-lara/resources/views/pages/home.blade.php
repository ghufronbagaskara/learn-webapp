<x-app-layout :seo="$seo ?? null" :page-title="$pageTitle ?? 'Home'">
    <section class="grid gap-6 lg:grid-cols-3">
        <article class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">{{ $page?->title ?? 'Welcome to Maxian Corp' }}</h1>
            <div class="mt-4 prose prose-sm max-w-none text-gray-700">
                {!! $page?->content ?? '<p>Build your corporate website and content operations from one dashboard.</p>' !!}
            </div>
        </article>

        <aside class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-base font-medium text-gray-700">Quick Links</h2>
            <nav class="mt-4 space-y-2">
                <a href="{{ route('blog.index') }}" class="block text-sm text-indigo-600 hover:text-indigo-700">Read our blog</a>
                <a href="{{ route('login') }}" class="block text-sm text-indigo-600 hover:text-indigo-700">Open admin panel</a>
            </nav>
        </aside>
    </section>

    <section class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <header class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">Latest Articles</h2>
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition">See all</a>
        </header>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            @forelse($posts as $post)
                <article class="border border-gray-100 rounded-xl p-4" wire:key="home-post-{{ $post->id }}">
                    <h3 class="text-base font-medium text-gray-900">{{ $post->title }}</h3>
                    <p class="mt-2 text-sm text-gray-500">{{ $post->excerpt }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="mt-4 inline-block text-sm text-indigo-600 hover:text-indigo-700">Read more</a>
                </article>
            @empty
                <article class="md:col-span-3 text-center py-10 text-gray-400">No records found.</article>
            @endforelse
        </div>
    </section>
</x-app-layout>
