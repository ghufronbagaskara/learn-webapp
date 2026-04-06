@props(['seo' => null, 'pageTitle' => null])

<x-layouts.guest :seo="$seo" :page-title="$pageTitle">
    {{ $slot }}
</x-layouts.guest>
