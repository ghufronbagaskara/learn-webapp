<?php

namespace App\Http\Livewire\Admin;

use App\Models\Page;
use App\Services\SeoService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PageForm extends Component
{
  use AuthorizesRequests;

  public ?Page $page = null;

  #[Validate('required|string|min:3|max:191')]
  public string $title = '';

  #[Validate('nullable|string|max:191')]
  public string $slug = '';

  #[Validate('required|string|min:3')]
  public string $content = '';

  #[Validate('boolean')]
  public bool $isPublished = false;

  #[Validate('integer|min:0')]
  public int $order = 0;

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

  public function mount(?Page $page = null): void
  {
    if ($page?->exists) {
      $this->page = $page;
      $this->title = $page->title;
      $this->slug = $page->slug;
      $this->content = $page->content;
      $this->isPublished = $page->is_published;
      $this->order = $page->order;
      $this->metaTitle = $page->seoMeta?->meta_title;
      $this->metaDescription = $page->seoMeta?->meta_description;
      $this->metaKeywords = $page->seoMeta?->meta_keywords;
      $this->ogTitle = $page->seoMeta?->og_title;
      $this->ogDescription = $page->seoMeta?->og_description;
      $this->canonicalUrl = $page->seoMeta?->canonical_url;
    }
  }

  public function save(SeoService $seoService): void
  {
    $this->page
      ? $this->authorize('update', $this->page)
      : $this->authorize('create', Page::class);

    $this->validate();

    $baseSlug = $this->slug !== '' ? $this->slug : Str::slug($this->title);
    $slug = $this->uniqueSlug($baseSlug);

    $model = Page::updateOrCreate(
      ['id' => $this->page?->id],
      [
        'title' => $this->title,
        'slug' => $slug,
        'content' => $this->content,
        'is_published' => $this->isPublished,
        'order' => $this->order,
        'created_by' => Auth::id(),
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

    session()->flash('success', 'Page saved successfully.');

    $this->redirect(route('admin.pages.index'), navigate: true);
  }

  private function uniqueSlug(string $slug): string
  {
    $candidate = $slug;
    $index = 1;

    while (Page::query()
      ->where('slug', $candidate)
      ->when($this->page?->id, fn($query) => $query->where('id', '!=', $this->page->id))
      ->exists()
    ) {
      $candidate = $slug . '-' . $index;
      $index++;
    }

    return $candidate;
  }

  public function render()
  {
    return view('livewire.admin.page-form');
  }
}
