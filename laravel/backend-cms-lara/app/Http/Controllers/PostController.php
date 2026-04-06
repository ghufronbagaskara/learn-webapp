<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class PostController extends Controller
{
  public function index(): View
  {
    $posts = Post::with('author', 'seoMeta')
      ->where('is_published', true)
      ->latest('published_at')
      ->paginate(10);

    return view('pages.blog-index', [
      'posts' => $posts,
      'pageTitle' => 'Blog',
    ]);
  }

  public function show(string $slug): View
  {
    $post = Post::with('author', 'seoMeta')
      ->where('slug', $slug)
      ->where('is_published', true)
      ->firstOrFail();

    return view('pages.blog-show', [
      'post' => $post,
      'seo' => $post->seoMeta,
      'pageTitle' => $post->title,
    ]);
  }
}
