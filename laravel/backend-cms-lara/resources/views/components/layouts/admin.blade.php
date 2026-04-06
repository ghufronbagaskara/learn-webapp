@props([
    'seo' => null,
    'pageTitle' => null,
    'headerTitle' => null,
    'headerSubtitle' => null,
])

@include('layouts.admin', [
    'seo' => $seo,
    'pageTitle' => $pageTitle,
    'headerTitle' => $headerTitle,
    'headerSubtitle' => $headerSubtitle,
    'slot' => $slot,
])
