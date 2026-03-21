<?php // app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
  public function handle($request, Closure $next, ...$roles)
  {
    $user = $request->user();

    if (!$user || !in_array($user->role, $roles, true)) {
      return response()->json([
        'success' => false,
        'message' => 'Role tidak memiliki izin',
        'errors' => null,
      ], 403);
    }

    return $next($request);
  }
}
