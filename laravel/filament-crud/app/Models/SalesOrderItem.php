<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderItem extends Model {
  use HasFactory;

  protected $fillable = [
    'sales_order_id',
    'product_id',
    'quantity',
    'price',
    'subtotal',
  ];

  protected $casts = [
    'price' => 'decimal:2',
    'subtotal' => 'decimal:2',
  ];

  public function salesOrder() {
    return $this->belongsTo(SalesOrder::class);
  }

  public function product() {
    return $this->belongsTo(Product::class);
  }

  protected static function boot() {
    parent::boot();

    static::saving(function ($item) {
      $item->subtotal = $item->quantity * $item->price;
    });

    static::saved(function ($item) {
      $item->salesOrder->calculateTotalAmount();
    });

    static::deleted(function ($item) {
      $item->salesOrder->calculateTotalAmount();
    });
  }
}
