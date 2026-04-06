<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
  public function show(string $slug): View
  {
    $page = Page::with('seoMeta')
      ->where('slug', $slug)
      ->where('is_published', true)
      ->firstOrFail();

    return view('pages.show', [
      'page' => $page,
      'seo' => $page->seoMeta,
      'pageTitle' => $page->title,
    ]);
  }
}
