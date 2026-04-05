<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
  /** @use HasFactory<\Database\Factories\CommentFactory> */
  use HasFactory, SoftDeletes;

  /**
   * @var list<string>
   */
  protected $fillable = [
    'blog_post_id',
    'user_id',
    'parent_id',
    'content',
    'is_approved',
  ];

  /**
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'is_approved' => 'bool',
    ];
  }

  /**
   * Get the post this comment belongs to.
   *
   * @return BelongsTo<BlogPost, $this>
   */
  public function blogPost(): BelongsTo
  {
    return $this->belongsTo(BlogPost::class);
  }

  /**
   * Get the user who authored this comment.
   *
   * @return BelongsTo<User, $this>
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Get the parent comment.
   *
   * @return BelongsTo<Comment, $this>
   */
  public function parent(): BelongsTo
  {
    return $this->belongsTo(self::class, 'parent_id');
  }

  /**
   * Get child replies for this comment.
   *
   * @return HasMany<Comment, $this>
   */
  public function replies(): HasMany
  {
    return $this->hasMany(self::class, 'parent_id');
  }

  /**
   * Scope approved comments.
   *
   * @param Builder<Comment> $query
   * @return Builder<Comment>
   */
  public function scopeApproved(Builder $query): Builder
  {
    return $query->where('is_approved', true);
  }
}
