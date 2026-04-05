<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\NewsletterConfirmMail;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterService
{
  /**
   * Subscribe email and send double opt-in confirmation.
   */
  public function subscribe(string $email): Subscriber
  {
    $subscriber = Subscriber::query()->firstOrCreate(
      ['email' => $email],
      ['token' => Str::random(48)],
    );

    if ($subscriber->token === null || $subscriber->token === '') {
      $subscriber->update(['token' => Str::random(48)]);
      $subscriber->refresh();
    }

    Mail::to($subscriber->email)->queue(new NewsletterConfirmMail($subscriber));

    return $subscriber;
  }

  /**
   * Confirm subscriber email using token.
   */
  public function confirm(string $token): ?Subscriber
  {
    $subscriber = Subscriber::query()->where('token', $token)->first();

    if ($subscriber === null) {
      return null;
    }

    $subscriber->update(['verified_at' => now()]);

    return $subscriber->refresh();
  }

  /**
   * Unsubscribe token owner.
   */
  public function unsubscribe(string $token): bool
  {
    return Subscriber::query()->where('token', $token)->delete() > 0;
  }
}
