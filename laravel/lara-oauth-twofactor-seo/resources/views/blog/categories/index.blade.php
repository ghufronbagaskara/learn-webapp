<x-layouts::app :title="__('Kategori')">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Kategori</h1>
            <a href="{{ route('categories.create') }}" class="rounded-lg bg-indigo-600 px-3 py-2 text-sm text-white">Tambah Kategori</a>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:bg-zinc-900">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-zinc-500">
                        <th class="pb-2">Nama</th>
                        <th class="pb-2">Slug</th>
                        <th class="pb-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr class="border-t border-zinc-100">
                            <td class="py-2">{{ $category->name }}</td>
                            <td class="py-2">{{ $category->slug }}</td>
                            <td class="py-2">
                                <a href="{{ route('categories.edit', $category) }}" class="text-indigo-600">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">{{ $categories->links() }}</div>
        </div>
    </div>
</x-layouts::app>
