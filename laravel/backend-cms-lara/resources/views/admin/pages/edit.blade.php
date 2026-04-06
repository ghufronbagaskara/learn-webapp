<x-admin-layout :page-title="$pageTitle" :header-title="$pageTitle">
    <section>
        @livewire(\App\Http\Livewire\Admin\PageForm::class, ['page' => $pageModel], key('page-form-'.$pageModel->id))
    </section>
</x-admin-layout>
