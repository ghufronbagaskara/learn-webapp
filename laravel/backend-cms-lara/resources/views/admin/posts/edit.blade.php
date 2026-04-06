<x-admin-layout :page-title="$pageTitle" :header-title="$pageTitle">
    <section>
        @livewire(\App\Http\Livewire\Admin\PostForm::class, ['post' => $postModel], key('post-form-'.$postModel->id))
    </section>
</x-admin-layout>
