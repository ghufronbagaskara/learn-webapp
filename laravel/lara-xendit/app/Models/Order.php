<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
  use HasFactory;

  protected $fillable = [
    'customer_name',
    'customer_email',
    'customer_phone',
    'amount',
    'description',
    'status',
  ];

  protected $casts = [
    'amount' => 'decimal:2',
  ];

  // relations
  public function payments() {
    return $this->hasMany(Payment::class);
  }

  public function latestPayment() {
    return $this->hasOne(Payment::class)->latestOfMany();
  }

  // helpers
  public function isPending() {
    return $this->status === 'pending';
  }

  public function isPaid() {
    return $this->status === 'paid';
  }

  public function markAsPaid() {
    return $this->update(['status' => 'paid']);
  }

  public function markAsCancelled() {
    return $this->update(['status' => 'cancelled']);
  }

  public function hasPendingPayment() {
    return $this->payments()->where('status', 'PENDING')->exists();
  }
}
