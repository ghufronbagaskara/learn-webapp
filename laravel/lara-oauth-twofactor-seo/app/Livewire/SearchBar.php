<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\BlogPost;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class SearchBar extends Component
{
  public string $query = '';

  /** @var Collection<int, BlogPost> */
  public Collection $results;

  /**
   * Initialize search component.
   */
  public function mount(): void
  {
    $this->results = new Collection();
  }

  /**
   * Update results when query changes.
   */
  public function updatedQuery(string $value): void
  {
    if (trim($value) === '') {
      $this->results = new Collection();

      return;
    }

    $keys = BlogPost::search($value)->keys();

    $this->results = BlogPost::query()
      ->published()
      ->whereIn('id', $keys)
      ->latest('published_at')
      ->limit(8)
      ->get();
  }

  /**
   * Render component view.
   */
  public function render(): View
  {
    return view('livewire.search-bar');
  }
}
