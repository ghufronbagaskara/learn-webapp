@props([
    'title' => 'Are you sure?',
    'message' => 'This action cannot be undone.',
    'trigger' => 'Delete',
])

<div x-data="{ open: false }" class="inline-block">
    <button type="button" @click="open = true" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        {{ $trigger }}
    </button>

    <div x-show="open" x-cloak class="fixed inset-0 bg-gray-900/30 flex items-center justify-center p-4 z-50">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-medium text-gray-700">{{ $title }}</h3>
            <p class="text-sm text-gray-500 mt-2">{{ $message }}</p>

            <div class="mt-6 flex items-center justify-end gap-2">
                <button type="button" @click="open = false" class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition">Cancel</button>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
