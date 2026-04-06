<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
  public function before(User $user, string $ability): ?bool
  {
    return $user->role === 'admin' ? true : null;
  }

  public function viewAny(User $user): bool
  {
    return $user->role === 'editor';
  }

  public function view(User $user, Post $post): bool
  {
    return $user->role === 'editor';
  }

  public function create(User $user): bool
  {
    return $user->role === 'editor';
  }

  public function update(User $user, Post $post): bool
  {
    return $user->role === 'editor';
  }

  public function delete(User $user, Post $post): bool
  {
    return $user->role === 'editor';
  }

  public function restore(User $user, Post $post): bool
  {
    return false;
  }

  public function forceDelete(User $user, Post $post): bool
  {
    return false;
  }
}
