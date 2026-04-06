<?php

namespace App\Http\Livewire\Admin;

use App\Models\Post;
use App\Services\SeoService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class PostForm extends Component
{
  use AuthorizesRequests, WithFileUploads;

  public ?Post $post = null;

  #[Validate('required|string|min:3|max:191')]
  public string $title = '';

  #[Validate('nullable|string|max:191')]
  public string $slug = '';

  #[Validate('required|string|min:10')]
  public string $excerpt = '';

  #[Validate('required|string|min:10')]
  public string $body = '';

  #[Validate('nullable|string|max:100')]
  public ?string $category = null;

  #[Validate('boolean')]
  public bool $isPublished = false;

  #[Validate('nullable|date')]
  public ?string $publishedAt = null;

  #[Validate('nullable|image|max:2048')]
  public $coverImageUpload;

  public ?string $coverImagePath = null;

  #[Validate('nullable|string|max:191')]
  public ?string $metaTitle = null;

  #[Validate('nullable|string')]
  public ?string $metaDescription = null;

  #[Validate('nullable|string')]
  public ?string $metaKeywords = null;

  #[Validate('nullable|string|max:191')]
  public ?string $ogTitle = null;

  #[Validate('nullable|string')]
  public ?string $ogDescription = null;

  #[Validate('nullable|url|max:191')]
  public ?string $canonicalUrl = null;

  public function mount(?Post $post = null): void
  {
    if ($post?->exists) {
      $this->post = $post;
      $this->title = $post->title;
      $this->slug = $post->slug;
      $this->excerpt = $post->excerpt;
      $this->body = $post->body;
      $this->category = $post->category;
      $this->isPublished = $post->is_published;
      $this->publishedAt = $post->published_at?->format('Y-m-d\TH:i');
      $this->coverImagePath = $post->cover_image;
      $this->metaTitle = $post->seoMeta?->meta_title;
      $this->metaDescription = $post->seoMeta?->meta_description;
      $this->metaKeywords = $post->seoMeta?->meta_keywords;
      $this->ogTitle = $post->seoMeta?->og_title;
      $this->ogDescription = $post->seoMeta?->og_description;
      $this->canonicalUrl = $post->seoMeta?->canonical_url;
    }
  }

  public function save(SeoService $seoService): void
  {
    $this->post
      ? $this->authorize('update', $this->post)
      : $this->authorize('create', Post::class);

    $this->validate();

    $baseSlug = $this->slug !== '' ? $this->slug : Str::slug($this->title);
    $slug = $this->uniqueSlug($baseSlug);

    if ($this->coverImageUpload) {
      if ($this->coverImagePath) {
        Storage::disk('public')->delete($this->coverImagePath);
      }

      $this->coverImagePath = $this->coverImageUpload->store('posts', 'public');
    }

    $model = Post::updateOrCreate(
      ['id' => $this->post?->id],
      [
        'title' => $this->title,
        'slug' => $slug,
        'excerpt' => $this->excerpt,
        'body' => $this->body,
        'cover_image' => $this->coverImagePath,
        'category' => $this->category,
        'is_published' => $this->isPublished,
        'published_at' => $this->isPublished ? ($this->publishedAt ?: now()) : null,
        'author_id' => Auth::id(),
      ],
    );

    $seoService->save($model, [
      'meta_title' => $this->metaTitle,
      'meta_description' => $this->metaDescription,
      'meta_keywords' => $this->metaKeywords,
      'og_title' => $this->ogTitle,
      'og_description' => $this->ogDescription,
      'canonical_url' => $this->canonicalUrl,
    ]);

    session()->flash('success', 'Post saved successfully.');

    $this->redirect(route('admin.posts.index'), navigate: true);
  }

  private function uniqueSlug(string $slug): string
  {
    $candidate = $slug;
    $index = 1;

    while (Post::query()
      ->where('slug', $candidate)
      ->when($this->post?->id, fn($query) => $query->where('id', '!=', $this->post->id))
      ->exists()
    ) {
      $candidate = $slug . '-' . $index;
      $index++;
    }

    return $candidate;
  }

  public function render()
  {
    return view('livewire.admin.post-form');
  }
}
