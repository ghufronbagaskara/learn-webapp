<x-layouts::app :title="__('Moderasi Komentar')">
    <div class="space-y-4">
        <h1 class="text-2xl font-bold">Moderasi Komentar</h1>

        <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:bg-zinc-900">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-zinc-500">
                        <th class="pb-2">Post</th>
                        <th class="pb-2">User</th>
                        <th class="pb-2">Isi</th>
                        <th class="pb-2">Status</th>
                        <th class="pb-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comments as $comment)
                        <tr class="border-t border-zinc-100">
                            <td class="py-2">{{ $comment->blogPost->title }}</td>
                            <td class="py-2">{{ $comment->user->name }}</td>
                            <td class="py-2">{{ \Illuminate\Support\Str::limit($comment->content, 60) }}</td>
                            <td class="py-2">{{ $comment->is_approved ? 'Approved' : 'Pending' }}</td>
                            <td class="py-2">
                                <form method="POST" action="{{ route('comments.update', $comment) }}" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_approved" value="{{ $comment->is_approved ? 0 : 1 }}" />
                                    <button type="submit" class="text-indigo-600">{{ $comment->is_approved ? 'Unapprove' : 'Approve' }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">{{ $comments->links() }}</div>
        </div>
    </div>
</x-layouts::app>
