<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
  /** Show the login form view. */
  public function createLogin(): View
  {
    return view('auth.login');
  }

  /** Authenticate user and redirect to reporting page. */
  public function storeLogin(Request $request): RedirectResponse
  {
    $credentials = $request->validate([
      'email' => ['required', 'email'],
      'password' => ['required', 'string'],
    ]);

    if (! Auth::attempt($credentials, remember: (bool) $request->boolean('remember'))) {
      return back()
        ->withErrors(['email' => 'Email atau password tidak valid.'])
        ->onlyInput('email');
    }

    $request->session()->regenerate();

    return redirect()->intended(route('reports.sales'));
  }

  /** Show the registration form view. */
  public function createRegister(): View
  {
    return view('auth.register');
  }

  /** Register a new user and sign them in. */
  public function storeRegister(Request $request): RedirectResponse
  {
    $validated = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255', 'unique:users,email'],
      'password' => ['required', 'string', 'confirmed', 'min:8'],
    ]);

    $user = User::query()->create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'password' => Hash::make($validated['password']),
    ]);

    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->route('reports.sales');
  }

  /** Logout active user and invalidate current session. */
  public function destroy(Request $request): RedirectResponse
  {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
  }
}
