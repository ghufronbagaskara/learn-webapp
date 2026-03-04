<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model {
  use HasFactory;

  protected $fillable = [
    'customer_id',
    'order_date',
    'total_amount',
    'status',
  ];

  protected $casts = [
    'order_date' => 'date',
    'total_amount' => 'decimal:2',
  ];

  public function customer() {
    return $this->belongsTo(Customer::class);
  }

  public function items() {
    return $this->hasMany(SalesOrderItem::class);
  }

  public function calculateTotalAmount() {
    $this->total_amount = $this->items()->sum('subtotal');
    $this->save();
  }

  public function markAsPaid() {
    $this->update(['status' => 'paid']);
  }
}
