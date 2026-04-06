<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Post;
use App\Models\Survey;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
  public function index(): View
  {
    return view('admin.dashboard', [
      'pageTitle' => 'Dashboard',
      'pagesCount' => Page::count(),
      'postsCount' => Post::count(),
      'surveysCount' => Survey::count(),
      'usersCount' => User::count(),
    ]);
  }
}
