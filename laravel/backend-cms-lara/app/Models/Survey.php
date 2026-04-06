<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Survey extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'title',
    'description',
    'json_schema',
    'is_active',
    'created_by',
  ];

  protected function casts(): array
  {
    return [
      'json_schema' => 'array',
      'is_active' => 'boolean',
    ];
  }

  public function responses(): HasMany
  {
    return $this->hasMany(SurveyResponse::class);
  }

  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }
}
