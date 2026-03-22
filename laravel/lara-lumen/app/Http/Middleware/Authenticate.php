<?php // app/Http/Middleware/Authenticate.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authenticate
{
  public function handle($request, Closure $next)
  {
    try {
      $user = JWTAuth::parseToken()->authenticate();

      if (!$user) {
        return response()->json([
          'success' => false,
          'message' => 'Token tidak valid',
          'errors' => null,
        ], 401);
      }

      auth()->setUser($user);
      $request->setUserResolver(static fn() => $user);
    } catch (JWTException $e) {
      Log::warning('JWT authentication failed', [
        'error' => $e->getMessage(),
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Token tidak ada, invalid, atau expired',
        'errors' => null,
      ], 401);
    }

    return $next($request);
  }
}
