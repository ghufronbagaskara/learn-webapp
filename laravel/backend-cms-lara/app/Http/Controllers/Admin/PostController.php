<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PostController extends Controller
{
  public function __construct(private readonly SeoService $seoService) {}

  public function index(): View
  {
    $this->authorize('viewAny', Post::class);

    return view('admin.posts.index', [
      'posts' => Post::with('author', 'seoMeta')->latest()->paginate(10),
      'pageTitle' => 'Posts',
    ]);
  }

  public function create(): View
  {
    $this->authorize('create', Post::class);

    return view('admin.posts.create', ['pageTitle' => 'Create Post']);
  }

  public function store(Request $request): RedirectResponse
  {
    $this->authorize('create', Post::class);

    $validated = $request->validate([
      'title' => ['required', 'string', 'max:191'],
      'slug' => ['nullable', 'string', 'max:191', 'unique:posts,slug'],
      'excerpt' => ['required', 'string'],
      'body' => ['required', 'string'],
      'cover_image' => ['nullable', 'image', 'max:2048'],
      'category' => ['nullable', 'string', 'max:100'],
      'is_published' => ['nullable', 'boolean'],
      'published_at' => ['nullable', 'date'],
      'meta_title' => ['nullable', 'string', 'max:191'],
      'meta_description' => ['nullable', 'string'],
      'meta_keywords' => ['nullable', 'string'],
      'og_title' => ['nullable', 'string', 'max:191'],
      'og_description' => ['nullable', 'string'],
      'canonical_url' => ['nullable', 'url', 'max:191'],
    ]);

    $slug = $validated['slug'] ?: Str::slug($validated['title']);
    $validated['slug'] = $this->makeUniqueSlug($slug);

    if ($request->hasFile('cover_image')) {
      $validated['cover_image'] = $request->file('cover_image')->store('posts', 'public');
    }

    $isPublished = (bool) ($validated['is_published'] ?? false);

    $post = Post::create([
      'title' => $validated['title'],
      'slug' => $validated['slug'],
      'excerpt' => $validated['excerpt'],
      'body' => $validated['body'],
      'cover_image' => $validated['cover_image'] ?? null,
      'category' => $validated['category'] ?? null,
      'is_published' => $isPublished,
      'published_at' => $isPublished ? ($validated['published_at'] ?? now()) : null,
      'author_id' => $request->user()->id,
    ]);

    $this->seoService->save($post, $validated);

    session()->flash('success', 'Post created successfully.');

    return redirect()->route('admin.posts.index');
  }

  public function edit(Post $post): View
  {
    $this->authorize('update', $post);

    return view('admin.posts.edit', [
      'postModel' => $post->load('seoMeta'),
      'pageTitle' => 'Edit Post',
    ]);
  }

  public function show(Post $post): RedirectResponse
  {
    $this->authorize('view', $post);

    return redirect()->route('admin.posts.edit', $post);
  }

  public function update(Request $request, Post $post): RedirectResponse
  {
    $this->authorize('update', $post);

    $validated = $request->validate([
      'title' => ['required', 'string', 'max:191'],
      'slug' => ['nullable', 'string', 'max:191', Rule::unique('posts', 'slug')->ignore($post->id)],
      'excerpt' => ['required', 'string'],
      'body' => ['required', 'string'],
      'cover_image' => ['nullable', 'image', 'max:2048'],
      'category' => ['nullable', 'string', 'max:100'],
      'is_published' => ['nullable', 'boolean'],
      'published_at' => ['nullable', 'date'],
      'meta_title' => ['nullable', 'string', 'max:191'],
      'meta_description' => ['nullable', 'string'],
      'meta_keywords' => ['nullable', 'string'],
      'og_title' => ['nullable', 'string', 'max:191'],
      'og_description' => ['nullable', 'string'],
      'canonical_url' => ['nullable', 'url', 'max:191'],
    ]);

    $slug = $validated['slug'] ?: Str::slug($validated['title']);
    $validated['slug'] = $this->makeUniqueSlug($slug, $post->id);

    if ($request->hasFile('cover_image')) {
      if ($post->cover_image) {
        Storage::disk('public')->delete($post->cover_image);
      }

      $validated['cover_image'] = $request->file('cover_image')->store('posts', 'public');
    }

    $isPublished = (bool) ($validated['is_published'] ?? false);

    $post->update([
      'title' => $validated['title'],
      'slug' => $validated['slug'],
      'excerpt' => $validated['excerpt'],
      'body' => $validated['body'],
      'cover_image' => $validated['cover_image'] ?? $post->cover_image,
      'category' => $validated['category'] ?? null,
      'is_published' => $isPublished,
      'published_at' => $isPublished ? ($validated['published_at'] ?? now()) : null,
    ]);

    $this->seoService->save($post, $validated);

    session()->flash('success', 'Post updated successfully.');

    return redirect()->route('admin.posts.index');
  }

  public function destroy(Post $post): RedirectResponse
  {
    $this->authorize('delete', $post);

    $post->delete();

    session()->flash('success', 'Post deleted successfully.');

    return redirect()->route('admin.posts.index');
  }

  private function makeUniqueSlug(string $slug, ?int $ignoreId = null): string
  {
    $candidate = $slug;
    $index = 1;

    while (Post::query()
      ->where('slug', $candidate)
      ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
      ->exists()
    ) {
      $candidate = $slug . '-' . $index;
      $index++;
    }

    return $candidate;
  }
}
