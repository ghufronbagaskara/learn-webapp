<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\Searchable;

class BlogPost extends Model
{
  /** @use HasFactory<\Database\Factories\BlogPostFactory> */
  use HasFactory, Searchable, SoftDeletes;

  /**
   * @var list<string>
   */
  protected $fillable = [
    'user_id',
    'category_id',
    'title',
    'slug',
    'content',
    'excerpt',
    'featured_image',
    'status',
    'published_at',
    'meta_title',
    'meta_description',
    'meta_keywords',
    'og_image',
    'canonical_url',
  ];

  /**
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'published_at' => 'datetime',
    ];
  }

  /**
   * Get the user who authored this post.
   *
   * @return BelongsTo<User, $this>
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Get the category that this post belongs to.
   *
   * @return BelongsTo<Category, $this>
   */
  public function category(): BelongsTo
  {
    return $this->belongsTo(Category::class);
  }

  /**
   * Get tags linked to this post.
   *
   * @return BelongsToMany<Tag, $this>
   */
  public function tags(): BelongsToMany
  {
    return $this->belongsToMany(Tag::class);
  }

  /**
   * Get comments for this post.
   *
   * @return HasMany<Comment, $this>
   */
  public function comments(): HasMany
  {
    return $this->hasMany(Comment::class);
  }

  /**
   * Scope published posts only.
   *
   * @param Builder<BlogPost> $query
   * @return Builder<BlogPost>
   */
  public function scopePublished(Builder $query): Builder
  {
    return $query
      ->where('status', 'published')
      ->whereNotNull('published_at')
      ->where('published_at', '<=', now());
  }

  /**
   * Build the searchable payload for Scout indexing.
   *
   * @return array<string, mixed>
   */
  public function toSearchableArray(): array
  {
    return [
      'title' => $this->title,
      'content' => strip_tags($this->content),
      'excerpt' => $this->excerpt,
      'meta_keywords' => $this->meta_keywords,
    ];
  }

  /**
   * Use slug for route model binding.
   */
  public function getRouteKeyName(): string
  {
    return 'slug';
  }
}
