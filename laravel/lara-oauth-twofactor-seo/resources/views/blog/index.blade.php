<x-layouts::app :title="__('ABC Blog')">
    <div class="mx-auto w-full max-w-6xl space-y-8">
        <section class="rounded-2xl bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-600 p-8 text-white">
            <h1 class="text-3xl font-bold">ABC Blog</h1>
            <p class="mt-2 text-sm text-indigo-50">Platform artikel modern dengan OAuth, 2FA, SEO, dan Live Search.</p>
        </section>

        <div class="grid gap-6 lg:grid-cols-[260px_minmax(0,1fr)]">
            <aside class="space-y-6 rounded-xl border border-zinc-200 bg-white p-4 dark:bg-zinc-900">
                <livewire:search-bar />

                <div>
                    <h2 class="mb-2 text-sm font-semibold uppercase tracking-wide text-zinc-500">Kategori</h2>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="{{ route('blog.index') }}" class="{{ $categorySlug === '' ? 'font-semibold text-indigo-600' : 'text-zinc-600' }}">Semua Kategori</a>
                        </li>
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="{{ $categorySlug === $category->slug ? 'font-semibold text-indigo-600' : 'text-zinc-600' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h2 class="mb-2 text-sm font-semibold uppercase tracking-wide text-zinc-500">Tag</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($tags as $tag)
                            <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="rounded-full border px-3 py-1 text-xs {{ $tagSlug === $tag->slug ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-zinc-200 text-zinc-600' }}">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <form method="POST" action="{{ route('newsletter.subscribe') }}" class="space-y-2">
                    @csrf
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-zinc-500">Newsletter</h2>
                    <input type="email" name="email" required placeholder="email@anda.com" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                    <button type="submit" class="w-full rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">Subscribe</button>
                </form>
            </aside>

            <section class="space-y-4">
                @if (session('status'))
                    <p class="rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">{{ session('status') }}</p>
                @endif

                @foreach ($posts as $post)
                    <article class="rounded-xl border border-zinc-200 bg-white p-5 dark:bg-zinc-900">
                        <div class="mb-3 flex items-center gap-2 text-xs text-zinc-500">
                            <span>{{ $post->category->name }}</span>
                            <span>&bull;</span>
                            <span>{{ optional($post->published_at)->format('d M Y') }}</span>
                            <span>&bull;</span>
                            <span>{{ $post->user->name }}</span>
                        </div>

                        <h2 class="text-2xl font-bold">
                            <a href="{{ route('blog.show', $post) }}" class="hover:text-indigo-600">{{ $post->title }}</a>
                        </h2>

                        <p class="mt-3 text-sm text-zinc-600">{{ $post->excerpt }}</p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($post->tags as $tag)
                                <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="rounded-full bg-zinc-100 px-2.5 py-1 text-xs text-zinc-700">#{{ $tag->name }}</a>
                            @endforeach
                        </div>
                    </article>
                @endforeach

                <div>{{ $posts->links() }}</div>
            </section>
        </div>
    </div>
</x-layouts::app>
