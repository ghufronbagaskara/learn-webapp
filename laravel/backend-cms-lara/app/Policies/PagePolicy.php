<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\User;

class PagePolicy
{
  public function before(User $user, string $ability): ?bool
  {
    return $user->role === 'admin' ? true : null;
  }

  public function viewAny(User $user): bool
  {
    return $user->role === 'editor';
  }

  public function view(User $user, Page $page): bool
  {
    return $user->role === 'editor';
  }

  public function create(User $user): bool
  {
    return $user->role === 'editor';
  }

  public function update(User $user, Page $page): bool
  {
    return $user->role === 'editor';
  }

  public function delete(User $user, Page $page): bool
  {
    return $user->role === 'editor';
  }

  public function restore(User $user, Page $page): bool
  {
    return false;
  }

  public function forceDelete(User $user, Page $page): bool
  {
    return false;
  }
}
