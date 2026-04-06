<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-base font-medium text-gray-700">Survey List</h2>
        <a wire:navigate href="{{ route('admin.surveys.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">Create Survey</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wide text-xs">Title</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wide text-xs">Creator</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wide text-xs">Status</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-500 uppercase tracking-wide text-xs">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($surveys as $survey)
                    <tr wire:key="survey-{{ $survey->id }}">
                        <td class="px-4 py-3 text-gray-800">{{ $survey->title }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $survey->creator?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-800">
                            @if($survey->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a wire:navigate href="{{ route('admin.surveys.edit', $survey) }}" class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition">Edit</a>
                                <button wire:click="toggleActive({{ $survey->id }})" wire:loading.attr="disabled" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                                    {{ $survey->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
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

    <div class="mt-4">
        {{ $surveys->links() }}
    </div>
</div>
