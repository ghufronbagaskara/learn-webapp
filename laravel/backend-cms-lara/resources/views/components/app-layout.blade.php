@props(['seo' => null, 'pageTitle' => null])

<x-layouts.app :seo="$seo" :page-title="$pageTitle">
    {{ $slot }}
</x-layouts.app>
