<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
  /** @use HasFactory<\Database\Factories\TagFactory> */
  use HasFactory;

  /**
   * @var list<string>
   */
  protected $fillable = [
    'name',
    'slug',
  ];

  /**
   * Get all posts linked to this tag.
   *
   * @return BelongsToMany<BlogPost, $this>
   */
  public function blogPosts(): BelongsToMany
  {
    return $this->belongsToMany(BlogPost::class);
  }
}
