<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BlogPostController::class, 'index'])->name('home');
Route::get('/blog', [BlogPostController::class, 'index'])->name('blog.index');
Route::get('/blog/{blogPost:slug}', [BlogPostController::class, 'show'])->name('blog.show');

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::middleware(['auth'])->group(function (): void {
  Route::get('/2fa/challenge', [TwoFactorController::class, 'create'])->name('2fa.challenge');
  Route::post('/2fa/verify', [TwoFactorController::class, 'store'])->name('2fa.verify');
});

Route::middleware(['auth', 'verified', 'require-two-factor'])->group(function (): void {
    Route::view('dashboard', 'dashboard')->name('dashboard');

  Route::resource('blog', BlogPostController::class)->except(['index', 'show']);
  Route::resource('categories', CategoryController::class);
  Route::resource('comments', CommentController::class)->except(['store']);

  Route::post('/blog/{blogPost:slug}/comments', [CommentController::class, 'store'])->name('blog.comments.store');
});

require __DIR__.'/settings.php';
