<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialAuthService
{
  /**
   * Link or create an account from social OAuth callback data.
   */
  public function resolveUser(string $provider, SocialiteUser $oauthUser): User
  {
    return DB::transaction(function () use ($provider, $oauthUser): User {
      $email = $oauthUser->getEmail() ?: sprintf('%s_%s@oauth.local', $provider, $oauthUser->getId());

      $socialAccount = SocialAccount::query()
        ->where('provider', $provider)
        ->where('provider_id', $oauthUser->getId())
        ->first();

      if ($socialAccount !== null) {
        $socialAccount->update([
          'token' => $oauthUser->token,
          'refresh_token' => $oauthUser->refreshToken,
        ]);

        return $socialAccount->user;
      }

      $user = User::query()->where('email', $email)->first();

      if ($user === null) {
        $user = User::query()->create([
          'name' => $oauthUser->getName() ?: 'Pengguna OAuth',
          'email' => $email,
          'password' => null,
          'email_verified_at' => now(),
        ]);
      }

      $user->socialAccounts()->updateOrCreate(
        [
          'provider' => $provider,
          'provider_id' => $oauthUser->getId(),
        ],
        [
          'token' => $oauthUser->token,
          'refresh_token' => $oauthUser->refreshToken,
        ],
      );

      return $user;
    });
  }
}
