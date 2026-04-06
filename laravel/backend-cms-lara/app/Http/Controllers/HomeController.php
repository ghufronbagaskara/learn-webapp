<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use Illuminate\View\View;

class HomeController extends Controller
{
  public function index(): View
  {
    $homePage = Page::with('seoMeta')
      ->where('is_published', true)
      ->whereIn('slug', ['home', '/'])
      ->first();

    $posts = Post::with('author', 'seoMeta')
      ->where('is_published', true)
      ->latest('published_at')
      ->take(3)
      ->get();

    return view('pages.home', [
      'page' => $homePage,
      'posts' => $posts,
      'seo' => $homePage?->seoMeta,
      'pageTitle' => $homePage?->title ?? 'Corporate CMS',
    ]);
  }
}
