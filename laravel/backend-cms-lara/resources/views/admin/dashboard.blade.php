<x-admin-layout :page-title="$pageTitle" :header-title="$pageTitle" header-subtitle="Overview of your CMS data">
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm text-gray-500">Pages</p>
            <p class="text-2xl font-semibold text-gray-900 tracking-tight mt-2">{{ $pagesCount }}</p>
        </article>
        <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm text-gray-500">Posts</p>
            <p class="text-2xl font-semibold text-gray-900 tracking-tight mt-2">{{ $postsCount }}</p>
        </article>
        <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm text-gray-500">Surveys</p>
            <p class="text-2xl font-semibold text-gray-900 tracking-tight mt-2">{{ $surveysCount }}</p>
        </article>
        <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm text-gray-500">Users</p>
            <p class="text-2xl font-semibold text-gray-900 tracking-tight mt-2">{{ $usersCount }}</p>
        </article>
    </section>
</x-admin-layout>
