<x-admin-layout :page-title="$pageTitle" :header-title="$pageTitle">
    <section>
        @livewire(\App\Http\Livewire\Admin\PostForm::class)
    </section>
</x-admin-layout>
