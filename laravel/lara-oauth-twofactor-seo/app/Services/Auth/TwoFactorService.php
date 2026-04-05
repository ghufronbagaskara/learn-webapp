<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;

class TwoFactorService
{
  /**
   * Generate and store a new encrypted TOTP secret for the user.
   */
  public function generateSecret(User $user): string
  {
    $secret = app('pragmarx.google2fa')->generateSecretKey();

    $user->forceFill([
      'two_factor_secret' => encrypt($secret),
      'two_factor_enabled' => false,
      'two_factor_confirmed_at' => null,
    ])->save();

    return $secret;
  }

  /**
   * Build the QR code image (inline SVG/PNG data URI) for current secret.
   */
  public function qrCodeInline(User $user): ?string
  {
    $secret = $this->plainSecret($user);

    if ($secret === null) {
      return null;
    }

    $qrCode = app('pragmarx.google2fa')->getQRCodeInline(
      config('app.name', 'ABC Blog'),
      $user->email,
      $secret,
    );

    if (! is_string($qrCode) || trim($qrCode) === '') {
      return null;
    }

    // Some drivers return full <img ...> HTML while others return a data URI.
    if (str_starts_with(ltrim($qrCode), '<img')) {
      preg_match('/src=["\']([^"\']+)["\']/', $qrCode, $matches);

      return $matches[1] ?? null;
    }

    return $qrCode;
  }

  /**
   * Get decrypted TOTP secret for manual setup fallback.
   */
  public function plainSecret(User $user): ?string
  {
    if (empty($user->two_factor_secret)) {
      return null;
    }

    try {
      $secret = decrypt($user->two_factor_secret);
    } catch (\Throwable $exception) {
      report($exception);

      return null;
    }

    return is_string($secret) && $secret !== '' ? $secret : null;
  }

  /**
   * Build otpauth URI for authenticator apps when QR rendering fails.
   */
  public function otpauthUrl(User $user): ?string
  {
    $secret = $this->plainSecret($user);

    if ($secret === null) {
      return null;
    }

    $issuer = rawurlencode((string) config('app.name', 'ABC Blog'));
    $account = rawurlencode($user->email);

    return sprintf('otpauth://totp/%s:%s?secret=%s&issuer=%s', $issuer, $account, $secret, $issuer);
  }

  /**
   * Verify a TOTP code and activate 2FA for the user.
   */
  public function confirm(User $user, string $code): bool
  {
    $secret = $this->plainSecret($user);

    if ($secret === null) {
      return false;
    }

    $normalizedCode = $this->normalizeCode($code);

    if ($normalizedCode === null) {
      return false;
    }

    $valid = app('pragmarx.google2fa')->verifyKey($secret, $normalizedCode, 1);

    if (! $valid) {
      return false;
    }

    $user->forceFill([
      'two_factor_enabled' => true,
      'two_factor_confirmed_at' => now(),
    ])->save();

    return true;
  }

  /**
   * Verify a challenge code without mutating user state.
   */
  public function verifyChallenge(User $user, string $code): bool
  {
    $secret = $this->plainSecret($user);

    if (! $user->hasEnabledTotp() || $secret === null) {
      return true;
    }

    $normalizedCode = $this->normalizeCode($code);

    if ($normalizedCode === null) {
      return false;
    }

    return app('pragmarx.google2fa')->verifyKey($secret, $normalizedCode, 1);
  }

  /**
   * Disable two-factor authentication for the user.
   */
  public function disable(User $user): void
  {
    $user->forceFill([
      'two_factor_secret' => null,
      'two_factor_enabled' => false,
      'two_factor_confirmed_at' => null,
    ])->save();
  }

  /**
   * Normalize user OTP input to a pure 6-digit numeric code.
   */
  private function normalizeCode(string $code): ?string
  {
    $normalized = preg_replace('/\D+/', '', $code) ?? '';

    if (strlen($normalized) !== 6) {
      return null;
    }

    return $normalized;
  }
}
