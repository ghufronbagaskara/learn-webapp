<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Symfony\Component\Clock\now;

class Payment extends Model {
  use HasFactory;

  protected $fillable = [
    'order_id',
    'xendit_invoice_id',
    'external_id',
    'invoice_url',
    'amount',
    'currency',
    'payment_method',
    'payment_channel',
    'status',
    'xendit_response',
    'paid_at',
    'expires_at',
  ];

  protected $casts = [
    'amount' => 'decimal:2',
    'xendit_response' => 'array',
    'paid_at' => 'datetime',
    'expires_at' => 'datetime',
  ];

  public function order() {
    return $this->belongsTo(Order::class);
  }

  // scopes
  public function scopePending(Builder $query) {
    return $query->where('status', 'PENDING');
  }

  public function scopePaid(Builder $query) {
    return $query->where('status', 'PAID');
  }

  public function scopeExpired(Builder $query) {
    return $query->where('status', 'EXPIRED');
  }

  public function scopeRecent(Builder $query) {
    return $query->latest();
  }

  // helpers
  public function isPending() {
    return $this->status === 'PENDING';
  }

  public function isPaid() {
    return $this->status === 'PAID';
  }

  public function isExpired() {
    return $this->status === 'EXPIRED';
  }

  public function markAsPaid(?string $paymentMethod = null, ?string $paymentChannel = null) {
    $this->update([
      'status' => 'PAID',
      'paid_at' => now(),
      'payment_method' => $paymentMethod ?? $this->payment_method,
      'payment_channel' => $paymentChannel ?? $this->payment_channel
    ]);

    $this->order->markAsPaid();
  }

  public function markAsExpired() {
    $this->update([
      'status' => 'EXPIRED',
      'expires_at' => now()
    ]);
  }

  public function markAsFailed() {
    $this->update(['status' => 'FAILED']);
  }

  public function markAsCancelled() {
    $this->update(['status' => 'CANCELLED']);
  }

  // accessors
  public function getStatusBadgeAttributes() {
    return match ($this->status) {
      'PENDING' => 'bg-warning',
      'PAID' => 'bg-success',
      'EXPIRED', 'FAILED', 'CANCELLED'  => 'bg-danger',
      default => 'bg-secondary',
    };
  }
}
