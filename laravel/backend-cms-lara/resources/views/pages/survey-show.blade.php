<x-app-layout :page-title="$pageTitle">
    <section>
        @livewire(\App\Http\Livewire\Public\SurveyRunner::class, ['survey' => $survey])
    </section>
</x-app-layout>
