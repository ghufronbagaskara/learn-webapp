<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireTwoFactor
{
  /**
   * Handle an incoming request.
   *
   * @param  Closure(Request): (Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $user = $request->user();

    if ($user?->hasEnabledTotp() && ! $request->session()->get('two_factor_verified', false)) {
      if (! $request->routeIs('2fa.challenge', '2fa.verify', 'logout')) {
        return redirect()->route('2fa.challenge');
      }
    }

    return $next($request);
  }
}
