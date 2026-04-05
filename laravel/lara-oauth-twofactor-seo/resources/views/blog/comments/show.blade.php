<x-layouts::app :title="__('Detail Komentar')">
    <div class="mx-auto max-w-3xl rounded-xl border border-zinc-200 bg-white p-6 dark:bg-zinc-900">
        <h1 class="text-xl font-bold">Detail Komentar</h1>
        <p class="mt-3 text-sm text-zinc-700">{{ $comment->content }}</p>
    </div>
</x-layouts::app>
