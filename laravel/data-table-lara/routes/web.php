<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'welcome', [
  'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
  Route::inertia('dashboard', 'dashboard')->name('dashboard');

  Route::get('products/datatable', [ProductController::class, 'datatable'])->name('products.datatable');
  Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
  Route::resource('products', ProductController::class)->except('show', 'create', 'edit');
});

require __DIR__ . '/settings.php';
