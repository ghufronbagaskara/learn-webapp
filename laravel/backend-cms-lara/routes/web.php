<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/blog', [PostController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [PostController::class, 'show'])->name('blog.show');
Route::get('/surveys/{survey}', [SurveyController::class, 'show'])->name('surveys.show');

require __DIR__ . '/auth.php';

Route::prefix('admin')
  ->name('admin.')
  ->middleware(['auth', 'role:admin,editor'])
  ->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('pages', App\Http\Controllers\Admin\PageController::class);
    Route::resource('posts', App\Http\Controllers\Admin\PostController::class);
    Route::resource('surveys', App\Http\Controllers\Admin\SurveyController::class);

    Route::middleware('role:admin')->group(function () {
      Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    });
  });

Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');
