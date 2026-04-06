<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'title',
    'slug',
    'excerpt',
    'body',
    'cover_image',
    'category',
    'is_published',
    'published_at',
    'author_id',
  ];

  protected function casts(): array
  {
    return [
      'is_published' => 'boolean',
      'published_at' => 'datetime',
    ];
  }

  public function seoMeta(): MorphOne
  {
    return $this->morphOne(SeoMeta::class, 'seoable');
  }

  public function author(): BelongsTo
  {
    return $this->belongsTo(User::class, 'author_id');
  }
}
