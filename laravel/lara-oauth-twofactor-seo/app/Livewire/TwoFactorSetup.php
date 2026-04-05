<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\User;
use App\Services\Auth\TwoFactorService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TwoFactorSetup extends Component
{
  public bool $isEnabled = false;

  public ?string $qrCodeInline = null;

  public ?string $manualSecret = null;

  public ?string $otpauthUrl = null;

  #[Validate('required|string|min:6|max:8')]
  public string $code = '';

  /**
   * Initialize 2FA state.
   */
  public function mount(TwoFactorService $twoFactorService): void
  {
    $user = Auth::user();

    if (! $user instanceof User) {
      return;
    }

    $this->isEnabled = $user->hasEnabledTotp();

    if (! $this->isEnabled && $user->two_factor_secret !== null) {
      $this->qrCodeInline = $twoFactorService->qrCodeInline($user);
      $this->manualSecret = $twoFactorService->plainSecret($user);
      $this->otpauthUrl = $twoFactorService->otpauthUrl($user);
    }
  }

  /**
   * Generate a secret and show QR code.
   */
  public function setup(TwoFactorService $twoFactorService): void
  {
    $user = Auth::user();

    if (! $user instanceof User) {
      return;
    }

    $twoFactorService->generateSecret($user);
    $freshUser = $user->refresh();

    $this->qrCodeInline = $twoFactorService->qrCodeInline($freshUser);
    $this->manualSecret = $twoFactorService->plainSecret($freshUser);
    $this->otpauthUrl = $twoFactorService->otpauthUrl($freshUser);
    $this->isEnabled = false;
    $this->code = '';
  }

  /**
   * Confirm TOTP code and activate 2FA.
   */
  public function confirm(TwoFactorService $twoFactorService): void
  {
    $validated = $this->validate();
    $user = Auth::user();

    if (! $user instanceof User) {
      return;
    }

    if (! $twoFactorService->confirm($user, $validated['code'])) {
      $this->addError('code', 'Kode OTP tidak valid.');

      return;
    }

    session()->put('two_factor_verified', true);
    $this->isEnabled = true;
    $this->qrCodeInline = null;
    $this->manualSecret = null;
    $this->otpauthUrl = null;
    $this->code = '';
    session()->flash('status', 'Two-factor authentication berhasil diaktifkan.');
  }

  /**
   * Disable 2FA for current user.
   */
  public function disable(TwoFactorService $twoFactorService): void
  {
    $user = Auth::user();

    if (! $user instanceof User) {
      return;
    }

    $twoFactorService->disable($user);
    session()->put('two_factor_verified', true);
    $this->isEnabled = false;
    $this->qrCodeInline = null;
    $this->manualSecret = null;
    $this->otpauthUrl = null;
    $this->code = '';
    session()->flash('status', 'Two-factor authentication berhasil dinonaktifkan.');
  }

  /**
   * Render component view.
   */
  public function render(): View
  {
    return view('livewire.two-factor-setup');
  }
}
