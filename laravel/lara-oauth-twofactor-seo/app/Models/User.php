<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'two_factor_enabled', 'two_factor_confirmed_at'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
  use HasFactory, Notifiable, SoftDeletes, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
      'two_factor_enabled' => 'bool',
      'two_factor_confirmed_at' => 'datetime',
        ];
    }

  /**
   * Get all blog posts authored by the user.
   *
   * @return HasMany<BlogPost, $this>
   */
  public function blogPosts(): HasMany
  {
    return $this->hasMany(BlogPost::class);
  }

  /**
   * Get all comments created by the user.
   *
   * @return HasMany<Comment, $this>
   */
  public function comments(): HasMany
  {
    return $this->hasMany(Comment::class);
  }

  /**
   * Get social OAuth accounts linked to the user.
   *
   * @return HasMany<SocialAccount, $this>
   */
  public function socialAccounts(): HasMany
  {
    return $this->hasMany(SocialAccount::class);
  }

  /**
   * Determine whether the user has 2FA enabled and confirmed.
   */
  public function hasEnabledTotp(): bool
  {
    return $this->two_factor_enabled && $this->two_factor_confirmed_at !== null;
  }

  /**
   * Get the user's initials
   */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}
