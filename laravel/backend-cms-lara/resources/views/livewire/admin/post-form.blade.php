<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <form wire:submit="save" class="space-y-6" enctype="multipart/form-data">
        <section>
            <h2 class="text-base font-medium text-gray-700 mb-4">Post Content</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input wire:model="title" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input wire:model="slug" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Excerpt</label>
                    <textarea wire:model="excerpt" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"></textarea>
                    @error('excerpt') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Body</label>
                    <textarea wire:model="body" rows="8" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"></textarea>
                    @error('body') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <input wire:model="category" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    @error('category') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Publish At</label>
                    <input wire:model="publishedAt" type="datetime-local" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    @error('publishedAt') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                    <input wire:model="coverImageUpload" type="file" accept="image/*" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    @error('coverImageUpload') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-end">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input wire:model="isPublished" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        Published
                    </label>
                </div>
            </div>

            @if($coverImageUpload)
                <article class="mt-4">
                    <p class="text-sm text-gray-500 mb-2">Preview</p>
                    <img src="{{ $coverImageUpload->temporaryUrl() }}" alt="Cover preview" class="h-40 w-full md:w-64 object-cover rounded-lg border border-gray-200">
                </article>
            @elseif($coverImagePath)
                <article class="mt-4">
                    <p class="text-sm text-gray-500 mb-2">Current cover</p>
                    <img src="{{ asset('storage/'.$coverImagePath) }}" alt="Cover image" class="h-40 w-full md:w-64 object-cover rounded-lg border border-gray-200">
                </article>
            @endif
        </section>

        <section>
            <h2 class="text-base font-medium text-gray-700 mb-4">SEO Metadata</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input wire:model="metaTitle" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    @error('metaTitle') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Canonical URL</label>
                    <input wire:model="canonicalUrl" type="url" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    @error('canonicalUrl') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <textarea wire:model="metaDescription" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"></textarea>
                    @error('metaDescription') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                    <input wire:model="metaKeywords" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    @error('metaKeywords') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">OG Title</label>
                    <input wire:model="ogTitle" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    @error('ogTitle') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">OG Description</label>
                    <input wire:model="ogDescription" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    @error('ogDescription') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <div class="flex justify-end gap-2">
            <a wire:navigate href="{{ route('admin.posts.index') }}" class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition">Cancel</a>
            <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <span wire:loading.remove>Save Post</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>
    </form>
</div>
