<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Requests\UpdateBlogPostRequest;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use App\Services\BlogPostService;
use App\Services\SeoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
  public function __construct(
    private readonly BlogPostService $blogPostService,
    private readonly SeoService $seoService,
  ) {}

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request): View
  {
    $search = (string) $request->string('q');
    $categorySlug = (string) $request->string('category');
    $tagSlug = (string) $request->string('tag');

    $posts = BlogPost::query()
      ->with(['user', 'category', 'tags'])
      ->published()
      ->when($search !== '', fn($query) => $query->whereIn('id', BlogPost::search($search)->keys()))
      ->when($categorySlug !== '', fn($query) => $query->whereHas('category', fn($categoryQuery) => $categoryQuery->where('slug', $categorySlug)))
      ->when($tagSlug !== '', fn($query) => $query->whereHas('tags', fn($tagQuery) => $tagQuery->where('slug', $tagSlug)))
      ->latest('published_at')
      ->paginate(9)
      ->withQueryString();

    $categories = Category::query()->orderBy('name')->get();
    $tags = Tag::query()->orderBy('name')->limit(20)->get();

    $this->seoService->setForListing(
      title: 'Artikel Terbaru - ABC Blog',
      description: 'Temukan artikel terbaru ABC Blog seputar web development, Laravel, dan produktivitas developer.',
    );

    return view('blog.index', compact('posts', 'categories', 'tags', 'search', 'categorySlug', 'tagSlug'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    $categories = Category::query()->orderBy('name')->get();
    $tags = Tag::query()->orderBy('name')->get();

    return view('blog.create', compact('categories', 'tags'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreBlogPostRequest $request): RedirectResponse
  {
    $post = $this->blogPostService->create($request->validated(), (int) $request->user()->id);

    return redirect()
      ->route('blog.show', $post)
      ->with('status', 'Artikel berhasil dibuat.');
  }

  /**
   * Display the specified resource.
   */
  public function show(BlogPost $blogPost): View
  {
    $blogPost->loadMissing(['user', 'category', 'tags', 'comments.user', 'comments.replies.user']);
    $this->seoService->setForPost($blogPost);

    return view('blog.show', ['post' => $blogPost]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(BlogPost $blog): View
  {
    $categories = Category::query()->orderBy('name')->get();
    $tags = Tag::query()->orderBy('name')->get();

    return view('blog.edit', [
      'post' => $blog->loadMissing('tags'),
      'categories' => $categories,
      'tags' => $tags,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateBlogPostRequest $request, BlogPost $blog): RedirectResponse
  {
    $post = $this->blogPostService->update($blog, $request->validated());

    return redirect()
      ->route('blog.show', $post)
      ->with('status', 'Artikel berhasil diperbarui.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(BlogPost $blog): RedirectResponse
  {
    $this->blogPostService->delete($blog);

    return redirect()
      ->route('blog.index')
      ->with('status', 'Artikel berhasil dihapus.');
  }
}
