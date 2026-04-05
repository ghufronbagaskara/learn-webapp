<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
  /** @use HasFactory<\Database\Factories\SubscriberFactory> */
  use HasFactory;

  /**
   * @var list<string>
   */
  protected $fillable = [
    'email',
    'token',
    'verified_at',
  ];

  /**
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'verified_at' => 'datetime',
    ];
  }
}
