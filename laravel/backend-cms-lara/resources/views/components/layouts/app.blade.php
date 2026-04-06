@props(['seo' => null, 'pageTitle' => null])

@include('layouts.app', [
    'seo' => $seo,
    'pageTitle' => $pageTitle,
    'slot' => $slot,
])
