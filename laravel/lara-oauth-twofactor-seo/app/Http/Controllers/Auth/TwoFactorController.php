<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\TwoFactorVerifyRequest;
use App\Models\User;
use App\Services\Auth\TwoFactorService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
  public function __construct(private readonly TwoFactorService $twoFactorService) {}

  /**
   * Show the challenge form.
   */
  public function create(): View|RedirectResponse
  {
    $user = Auth::user();

    if (! $user instanceof User || ! $user->hasEnabledTotp()) {
      return redirect()->route('dashboard');
    }

    return view('auth.two-factor-challenge');
  }

  /**
   * Verify challenge code and unlock session.
   */
  public function store(TwoFactorVerifyRequest $request): RedirectResponse
  {
    $user = $request->user();

    if ($user === null) {
      return redirect()->route('login');
    }

    $isValid = $this->twoFactorService->verifyChallenge($user, $request->validated('code'));

    if (! $isValid) {
      return back()->withErrors(['code' => 'Kode 2FA tidak valid.'])->withInput();
    }

    $request->session()->put('two_factor_verified', true);

    return redirect()->intended(route('dashboard'));
  }
}
