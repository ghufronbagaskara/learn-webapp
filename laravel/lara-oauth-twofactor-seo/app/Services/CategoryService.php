<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use App\Services\Support\SlugService;

class CategoryService
{
  public function __construct(private readonly SlugService $slugService) {}

  /**
   * Create a category.
   *
   * @param array<string, mixed> $data
   */
  public function create(array $data): Category
  {
    return Category::query()->create([
      'name' => $data['name'],
      'slug' => $this->slugService->uniqueSlug(Category::class, (string) $data['name']),
      'description' => $data['description'] ?? null,
    ]);
  }

  /**
   * Update a category.
   *
   * @param array<string, mixed> $data
   */
  public function update(Category $category, array $data): Category
  {
    $category->update([
      'name' => $data['name'],
      'slug' => $this->slugService->uniqueSlug(Category::class, (string) $data['name'], $category->id),
      'description' => $data['description'] ?? null,
    ]);

    return $category->refresh();
  }

  /**
   * Delete a category.
   */
  public function delete(Category $category): void
  {
    $category->delete();
  }
}
