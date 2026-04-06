<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
  public function index(): View
  {
    $this->authorize('viewAny', User::class);

    return view('admin.users.index', [
      'users' => User::latest()->paginate(10),
      'pageTitle' => 'Users',
    ]);
  }

  public function create(): View
  {
    $this->authorize('create', User::class);

    return view('admin.users.create', ['pageTitle' => 'Create User']);
  }

  public function store(Request $request): RedirectResponse
  {
    $this->authorize('create', User::class);

    $validated = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255', 'unique:users,email'],
      'password' => ['required', 'string', 'min:8'],
      'role' => ['required', Rule::in(['admin', 'editor'])],
    ]);

    User::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'password' => Hash::make($validated['password']),
      'role' => $validated['role'],
    ]);

    session()->flash('success', 'User created successfully.');

    return redirect()->route('admin.users.index');
  }

  public function show(User $user): RedirectResponse
  {
    return redirect()->route('admin.users.edit', $user);
  }

  public function edit(User $user): View
  {
    $this->authorize('update', $user);

    return view('admin.users.edit', [
      'userModel' => $user,
      'pageTitle' => 'Edit User',
    ]);
  }

  public function update(Request $request, User $user): RedirectResponse
  {
    $this->authorize('update', $user);

    $validated = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
      'password' => ['nullable', 'string', 'min:8'],
      'role' => ['required', Rule::in(['admin', 'editor'])],
    ]);

    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->role = $validated['role'];

    if (! empty($validated['password'])) {
      $user->password = Hash::make($validated['password']);
    }

    $user->save();

    session()->flash('success', 'User updated successfully.');

    return redirect()->route('admin.users.index');
  }

  public function destroy(User $user): RedirectResponse
  {
    $this->authorize('delete', $user);

    if (Auth::id() === $user->id) {
      session()->flash('error', 'You cannot delete your own account.');

      return redirect()->route('admin.users.index');
    }

    $user->delete();

    session()->flash('success', 'User deleted successfully.');

    return redirect()->route('admin.users.index');
  }
}
