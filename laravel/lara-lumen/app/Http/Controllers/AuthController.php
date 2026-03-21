<?php // app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
  public function register(Request $request): JsonResponse
  {
    $this->validate($request, [
      'name' => 'required|string|max:100',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role' => 'customer',
    ]);

    $token = JWTAuth::fromUser($user);

    return response()->json([
      'success' => true,
      'message' => 'Registrasi berhasil',
      'data' => [
        'user' => $user,
        'token' => $token,
      ],
    ], 201);
  }

  public function login(Request $request): JsonResponse
  {
    $this->validate($request, [
      'email' => 'required|email',
      'password' => 'required|string',
    ]);

    $credentials = $request->only(['email', 'password']);
    $token = auth()->attempt($credentials);

    if (!$token) {
      return response()->json([
        'success' => false,
        'message' => 'Email atau password tidak valid',
        'errors' => null,
      ], 401);
    }

    return response()->json([
      'success' => true,
      'message' => 'Login berhasil',
      'data' => [
        'user' => auth()->user(),
        'token' => $token,
        'token_type' => 'bearer',
        'expires_in' => config('jwt.ttl') * 60,
      ],
    ], 200);
  }

  public function profile(Request $request): JsonResponse
  {
    return response()->json([
      'success' => true,
      'message' => 'Profil user berhasil diambil',
      'data' => $request->user(),
    ], 200);
  }

  public function logout(): JsonResponse
  {
    auth()->logout();

    return response()->json([
      'success' => true,
      'message' => 'Logout berhasil',
      'data' => null,
    ], 200);
  }
}
