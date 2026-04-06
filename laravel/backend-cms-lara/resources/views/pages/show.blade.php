<x-app-layout :seo="$seo ?? null" :page-title="$pageTitle">
    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <header class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">{{ $page->title }}</h1>
        </header>

        <section class="prose prose-sm max-w-none text-gray-700">
            {!! $page->content !!}
        </section>
    </article>
</x-app-layout>
