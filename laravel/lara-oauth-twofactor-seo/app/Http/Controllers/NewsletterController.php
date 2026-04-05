<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SubscribeRequest;
use App\Services\NewsletterService;
use Illuminate\Http\RedirectResponse;

class NewsletterController extends Controller
{
  public function __construct(private readonly NewsletterService $newsletterService) {}

  /**
   * Store subscriber email and send confirmation mail.
   */
  public function subscribe(SubscribeRequest $request): RedirectResponse
  {
    $this->newsletterService->subscribe($request->validated('email'));

    return back()->with('status', 'Silakan cek email untuk konfirmasi newsletter.');
  }

  /**
   * Confirm newsletter subscription token.
   */
  public function confirm(string $token): RedirectResponse
  {
    $subscriber = $this->newsletterService->confirm($token);

    if ($subscriber === null) {
      return redirect()->route('home')->with('status', 'Token konfirmasi tidak valid.');
    }

    return redirect()->route('home')->with('status', 'Langganan newsletter berhasil dikonfirmasi.');
  }

  /**
   * Unsubscribe subscriber by token.
   */
  public function unsubscribe(string $token): RedirectResponse
  {
    $this->newsletterService->unsubscribe($token);

    return redirect()->route('home')->with('status', 'Anda berhasil berhenti berlangganan newsletter.');
  }
}
