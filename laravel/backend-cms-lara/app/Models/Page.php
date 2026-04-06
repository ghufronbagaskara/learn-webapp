<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'title',
    'slug',
    'content',
    'is_published',
    'order',
    'created_by',
  ];

  protected function casts(): array
  {
    return [
      'is_published' => 'boolean',
    ];
  }

  public function seoMeta(): MorphOne
  {
    return $this->morphOne(SeoMeta::class, 'seoable');
  }

  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }
}
