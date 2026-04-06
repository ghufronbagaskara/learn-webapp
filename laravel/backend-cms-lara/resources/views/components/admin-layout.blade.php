@props([
    'seo' => null,
    'pageTitle' => null,
    'headerTitle' => null,
    'headerSubtitle' => null,
])

<x-layouts.admin :seo="$seo" :page-title="$pageTitle" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    {{ $slot }}
</x-layouts.admin>
