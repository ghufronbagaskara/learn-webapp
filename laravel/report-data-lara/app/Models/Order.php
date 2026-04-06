<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'order_number',
    'status',
    'category',
    'total',
  ];

  protected function casts(): array
  {
    return [
      'total' => 'decimal:2',
    ];
  }

  /** Filter query by created_at date range. */
  public function scopeFilterByDate(Builder $query, ?string $startDate, ?string $endDate): Builder
  {
    if (blank($startDate) || blank($endDate)) {
      return $query;
    }

    return $query->whereBetween('created_at', [$startDate, $endDate]);
  }

  /** Filter query by order status. */
  public function scopeFilterByStatus(Builder $query, ?string $status): Builder
  {
    if (blank($status)) {
      return $query;
    }

    return $query->where('status', $status);
  }

  /** Get the user that owns this order. */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
