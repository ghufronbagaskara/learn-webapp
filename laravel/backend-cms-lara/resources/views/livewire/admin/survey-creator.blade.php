<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Survey Title</label>
            <input wire:model="titleInput" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            @error('titleInput') <span class="mt-1 text-xs text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <input wire:model="descriptionInput" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            @error('descriptionInput') <span class="mt-1 text-xs text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="md:col-span-2">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                <input wire:model="isActive" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                Active survey
            </label>
        </div>
    </div>

    <div id="surveyCreatorContainer" class="h-[600px] border border-gray-200 rounded-xl overflow-hidden"></div>

    <div class="flex justify-end">
        <button wire:click="save" wire:loading.attr="disabled" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <span wire:loading.remove>Save Survey</span>
            <span wire:loading>Saving...</span>
        </button>
    </div>

    @script
    <script>
        const schema = @js($jsonSchema);
        const creator = new SurveyCreator.SurveyCreator({ showLogicTab: true });

        try {
            creator.JSON = JSON.parse(schema || '{}');
        } catch (error) {
            creator.JSON = {};
        }

        creator.render(document.getElementById('surveyCreatorContainer'));

        creator.onModified.add(() => {
            $wire.updateSchema(JSON.stringify(creator.JSON));
        });
    </script>
    @endscript
</div>
