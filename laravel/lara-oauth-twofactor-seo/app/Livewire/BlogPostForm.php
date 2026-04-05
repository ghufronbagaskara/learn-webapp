<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\BlogPostStatus;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use App\Services\BlogPostService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class BlogPostForm extends Component
{
  use WithFileUploads;

  public ?BlogPost $post = null;

  #[Validate('required|string|max:255')]
  public string $title = '';

  #[Validate('required|integer|exists:categories,id')]
  public ?int $category_id = null;

  #[Validate('required|string')]
  public string $content = '';

  #[Validate('nullable|string|max:320')]
  public ?string $excerpt = null;

  #[Validate('required|string|in:draft,published,archived')]
  public string $status = BlogPostStatus::Draft->value;

  /** @var array<int> */
  public array $tag_ids = [];

  /** @var mixed */
  #[Validate('nullable|image|max:2048')]
  public $featured_image;

  #[Validate('nullable|string|max:255')]
  public ?string $meta_title = null;

  #[Validate('nullable|string|max:255')]
  public ?string $meta_description = null;

  #[Validate('nullable|string|max:255')]
  public ?string $meta_keywords = null;

  #[Validate('nullable|url|max:2048')]
  public ?string $og_image = null;

  #[Validate('nullable|url|max:2048')]
  public ?string $canonical_url = null;

  /** @var Collection<int, Category> */
  public Collection $categories;

  /** @var Collection<int, Tag> */
  public Collection $tags;

  /**
   * Initialize component state.
   */
  public function mount(?BlogPost $post = null): void
  {
    $this->categories = Category::query()->orderBy('name')->get();
    $this->tags = Tag::query()->orderBy('name')->get();

    if ($post === null) {
      return;
    }

    $this->post = $post;
    $this->title = $post->title;
    $this->category_id = (int) $post->category_id;
    $this->content = $post->content;
    $this->excerpt = $post->excerpt;
    $this->status = $post->status;
    $this->tag_ids = $post->tags()->pluck('id')->map(fn(int $id): int => $id)->all();
    $this->meta_title = $post->meta_title;
    $this->meta_description = $post->meta_description;
    $this->meta_keywords = $post->meta_keywords;
    $this->og_image = $post->og_image;
    $this->canonical_url = $post->canonical_url;
  }

  /**
   * Persist blog post.
   */
  public function save(BlogPostService $blogPostService)
  {
    $validated = $this->validate();

    if ($this->post !== null) {
      $post = $blogPostService->update($this->post, $validated);

      session()->flash('status', 'Artikel berhasil diperbarui.');

      return $this->redirectRoute('blog.show', ['blogPost' => $post->slug], navigate: true);
    }

    $post = $blogPostService->create($validated, (int) Auth::id());

    session()->flash('status', 'Artikel berhasil dibuat.');

    return $this->redirectRoute('blog.show', ['blogPost' => $post->slug], navigate: true);
  }

  /**
   * Render component view.
   */
  public function render(): View
  {
    return view('livewire.blog-post-form', [
      'statuses' => BlogPostStatus::values(),
    ]);
  }
}
