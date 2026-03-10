<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
  use HasFactory;

  protected $fillable = [
    'name',
    'sku',
    'description',
    'category',
    'price',
    'stock',
    'status'
  ];

  protected $casts = [
    'price' => 'decimal:2',
    'stock' => 'integer',
  ];

  public function scopeActivty($query) {
    return $query->where('status', 'active');
  }

  public function getFormattedPriceAttribute() {
    return 'Rp ' . number_format($this->price, 0, ',', '.');
  }

  public function getStatusBadgeAttribute() {
    return match ($this->status) {
      'active' => 'success',
      'inactive' => 'danger',
      'draft' => 'warning',
      default => 'secondary',
    };
  }
}
