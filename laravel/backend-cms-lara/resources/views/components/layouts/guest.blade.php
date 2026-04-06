@props(['seo' => null, 'pageTitle' => null])

@include('layouts.guest', [
    'seo' => $seo,
    'pageTitle' => $pageTitle,
    'slot' => $slot,
])
