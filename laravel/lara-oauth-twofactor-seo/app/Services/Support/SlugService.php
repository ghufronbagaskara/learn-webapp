<?php

declare(strict_types=1);

namespace App\Services\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SlugService
{
  /**
   * Generate a unique slug for the given model and source text.
   *
   * @param class-string<Model> $modelClass
   */
  public function uniqueSlug(string $modelClass, string $source, ?int $ignoreId = null): string
  {
    $baseSlug = Str::slug($source);
    $slug = $baseSlug;
    $counter = 2;

    while ($this->slugExists($modelClass, $slug, $ignoreId)) {
      $slug = sprintf('%s-%d', $baseSlug, $counter);
      $counter++;
    }

    return $slug;
  }

  /**
   * Check whether a slug already exists.
   *
   * @param class-string<Model> $modelClass
   */
  public function slugExists(string $modelClass, string $slug, ?int $ignoreId = null): bool
  {
    $query = $modelClass::query()->where('slug', $slug);

    if ($ignoreId !== null) {
      $query->whereKeyNot($ignoreId);
    }

    return $query->exists();
  }
}
