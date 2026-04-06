@props(['seo' => null, 'title' => null, 'description' => null])

@php
    $metaTitle = $seo?->meta_title ?? $title ?? config('app.name');
    $metaDescription = $seo?->meta_description ?? $description ?? '';
    $ogTitle = $seo?->og_title ?? $metaTitle;
    $ogDescription = $seo?->og_description ?? $metaDescription;
    $ogImage = $seo?->og_image ? asset('storage/'.$seo->og_image) : asset('images/og-default.png');
    $canonical = $seo?->canonical_url ?? url()->current();
@endphp

<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
@if($seo?->meta_keywords)
    <meta name="keywords" content="{{ $seo->meta_keywords }}">
@endif
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:type" content="website">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $ogDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">
