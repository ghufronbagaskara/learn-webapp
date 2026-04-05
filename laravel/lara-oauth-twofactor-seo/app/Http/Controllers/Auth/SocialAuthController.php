<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialCallbackRequest;
use App\Services\Auth\SocialAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class SocialAuthController extends Controller
{
  public function __construct(private readonly SocialAuthService $socialAuthService) {}

  /**
   * Redirect to social provider authorization page.
   */
  public function redirect(SocialCallbackRequest $request): RedirectResponse
  {
    return Socialite::driver($request->validated('provider'))->redirect();
  }

  /**
   * Handle social provider callback.
   */
  public function callback(SocialCallbackRequest $request): RedirectResponse
  {
    $provider = $request->validated('provider');

    try {
      $oauthUser = Socialite::driver($provider)->user();
    } catch (InvalidStateException $exception) {
      report($exception);

      return redirect()
        ->route('login')
        ->with('status', __('Sesi login sosial sudah kedaluwarsa. Silakan coba login dengan :provider lagi.', ['provider' => ucfirst($provider)]));
    }

    $user = $this->socialAuthService->resolveUser($provider, $oauthUser);

    Auth::login($user, remember: true);
    session()->put('two_factor_verified', ! $user->hasEnabledTotp());

    return redirect()->intended(route('dashboard'));
  }
}
