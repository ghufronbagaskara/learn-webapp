<x-layouts::app :title="__('Tulis Artikel')">
    <div class="mx-auto w-full max-w-5xl space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Buat Artikel Baru</h1>
            <a href="{{ route('blog.index') }}" class="text-sm text-indigo-600 hover:underline">Kembali ke Blog</a>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:bg-zinc-900">
            <livewire:blog-post-form />
        </div>
    </div>
</x-layouts::app>
