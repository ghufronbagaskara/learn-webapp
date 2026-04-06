<x-admin-layout :page-title="$pageTitle" :header-title="$pageTitle">
    <section>
        @livewire(\App\Http\Livewire\Admin\SurveyCreator::class, ['survey' => $survey], key('survey-creator-'.$survey->id))
    </section>
</x-admin-layout>
