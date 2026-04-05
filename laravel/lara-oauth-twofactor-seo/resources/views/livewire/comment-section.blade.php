<section class="mt-10 space-y-6">
    <h3 class="text-xl font-semibold">Komentar</h3>

    @if (session('status'))
        <p class="rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">{{ session('status') }}</p>
    @endif

    <form wire:submit="submit" class="space-y-3 rounded-xl border border-zinc-200 p-4">
        @if ($parent_id)
            <div class="flex items-center justify-between rounded-md bg-zinc-50 px-3 py-2 text-sm">
                <span>Anda sedang membalas komentar #{{ $parent_id }}</span>
                <button wire:click.prevent="cancelReply" type="button" class="font-medium text-red-600">Batal</button>
            </div>
        @endif

        <textarea wire:model="content" rows="4" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" placeholder="Tulis komentar Anda..."></textarea>
        @error('content') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
            Kirim Komentar
        </button>
    </form>

    <div class="space-y-4">
        @forelse ($comments as $comment)
            <article class="rounded-xl border border-zinc-200 p-4">
                <div class="mb-2 flex items-center justify-between">
                    <p class="text-sm font-semibold">{{ $comment->user->name }}</p>
                    <button wire:click="replyTo({{ $comment->id }})" type="button" class="text-sm text-indigo-600">Balas</button>
                </div>
                <p class="text-sm text-zinc-700">{{ $comment->content }}</p>

                @if ($comment->replies->isNotEmpty())
                    <div class="mt-4 space-y-3 border-l border-zinc-200 pl-4">
                        @foreach ($comment->replies as $reply)
                            <div class="rounded-lg bg-zinc-50 p-3">
                                <p class="text-xs font-semibold text-zinc-600">{{ $reply->user->name }}</p>
                                <p class="text-sm">{{ $reply->content }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </article>
        @empty
            <p class="text-sm text-zinc-500">Belum ada komentar yang disetujui.</p>
        @endforelse
    </div>
</section>
