<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
  /** @use HasFactory<\Database\Factories\CategoryFactory> */
  use HasFactory;

  /**
   * @var list<string>
   */
  protected $fillable = [
    'name',
    'slug',
    'description',
  ];

  /**
   * Get all posts that belong to this category.
   *
   * @return HasMany<BlogPost, $this>
   */
  public function blogPosts(): HasMany
  {
    return $this->hasMany(BlogPost::class);
  }
}
