<?php

use App\Http\Controllers\AuthController;
use App\Livewire\Reports\PivotReport;
use App\Livewire\Reports\SalesReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
  return Auth::check()
    ? redirect()->route('reports.sales')
    : redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function (): void {
  Route::get('/login', [AuthController::class, 'createLogin'])->name('login');
  Route::post('/login', [AuthController::class, 'storeLogin'])->name('login.store');
  Route::get('/register', [AuthController::class, 'createRegister'])->name('register');
  Route::post('/register', [AuthController::class, 'storeRegister'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::prefix('reports')->middleware(['auth'])->name('reports.')->group(function (): void {
  Route::get('/sales', SalesReport::class)->name('sales');
  Route::get('/pivot', PivotReport::class)->name('pivot');
});
