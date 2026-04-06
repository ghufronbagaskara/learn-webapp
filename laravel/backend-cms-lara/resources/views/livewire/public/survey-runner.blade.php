<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
    <script src="https://unpkg.com/survey-core/survey.core.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/survey-core/defaultV2.min.css">

    <header>
        <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">{{ $survey->title }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ $survey->description }}</p>
    </header>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email (optional)</label>
        <input wire:model="respondentEmail" type="email" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="you@example.com">
        @error('respondentEmail') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div id="surveyRunnerContainer" class="rounded-xl border border-gray-200 p-4"></div>

    <div class="flex justify-end">
        <button type="button" wire:click="submit" wire:loading.attr="disabled" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <span wire:loading.remove>Submit Survey</span>
            <span wire:loading>Submitting...</span>
        </button>
    </div>

    @script
    <script>
        const surveyData = @js($surveySchemaJson);
        const surveyModel = new Survey.Model(JSON.parse(surveyData || '{}'));
        surveyModel.render(document.getElementById('surveyRunnerContainer'));

        surveyModel.onValueChanged.add(() => {
            $wire.updateResponse(JSON.stringify(surveyModel.data));
        });

        Livewire.on('survey-submitted', () => {
            surveyModel.clear(false, true);
        });
    </script>
    @endscript
</div>
