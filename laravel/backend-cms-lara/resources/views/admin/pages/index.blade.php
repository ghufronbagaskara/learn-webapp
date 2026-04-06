<x-admin-layout :page-title="$pageTitle" :header-title="$pageTitle">
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-medium text-gray-700">All pages</h2>
            <a wire:navigate href="{{ route('admin.pages.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">Create Page</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wide text-xs">Title</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wide text-xs">Slug</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wide text-xs">Status</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-500 uppercase tracking-wide text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pages as $page)
                        <tr wire:key="page-{{ $page->id }}">
                            <td class="px-4 py-3 text-gray-800">{{ $page->title }}</td>
                            <td class="px-4 py-3 text-gray-800">{{ $page->slug }}</td>
                            <td class="px-4 py-3 text-gray-800">
                                @if($page->is_published)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Published</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Draft</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a wire:navigate href="{{ route('admin.pages.edit', $page) }}" class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition">Edit</a>
                                    <x-confirm-modal title="Delete page?" message="This page will be moved to trash." trigger="Delete">
                                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">Delete</button>
                                        </form>
                                    </x-confirm-modal>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-10 text-gray-400">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $pages->links() }}</div>
    </section>
</x-admin-layout>
