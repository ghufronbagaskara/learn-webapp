<?php

namespace App\Policies;

use App\Models\Survey;
use App\Models\User;

class SurveyPolicy
{
  public function before(User $user, string $ability): ?bool
  {
    return $user->role === 'admin' ? true : null;
  }

  public function viewAny(User $user): bool
  {
    return $user->role === 'editor';
  }

  public function view(User $user, Survey $survey): bool
  {
    return $user->role === 'editor';
  }

  public function create(User $user): bool
  {
    return $user->role === 'editor';
  }

  public function update(User $user, Survey $survey): bool
  {
    return $user->role === 'editor';
  }

  public function delete(User $user, Survey $survey): bool
  {
    return $user->role === 'editor';
  }

  public function restore(User $user, Survey $survey): bool
  {
    return false;
  }

  public function forceDelete(User $user, Survey $survey): bool
  {
    return false;
  }
}
