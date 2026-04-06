<x-admin-layout :page-title="$pageTitle" :header-title="$pageTitle">
    <section>
        @livewire(\App\Http\Livewire\Admin\SurveyList::class)
    </section>
</x-admin-layout>
