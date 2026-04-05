<x-layouts::app :title="$category->name">
    <div class="mx-auto max-w-3xl rounded-xl border border-zinc-200 bg-white p-6 dark:bg-zinc-900">
        <h1 class="text-2xl font-bold">{{ $category->name }}</h1>
        <p class="mt-3 text-sm text-zinc-600">{{ $category->description ?: 'Tidak ada deskripsi.' }}</p>
    </div>
</x-layouts::app>
