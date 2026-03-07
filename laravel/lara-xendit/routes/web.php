<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return redirect()->route('orders.index');
});

// orders
Route::resource('orders', OrderController::class);


// payments
Route::prefix('payments')->name('payments.')->group(function () {
  Route::get('/', [PaymentController::class, 'index'])->name('index');
  Route::get('/success-page', [PaymentController::class, 'success'])->name('success');
  Route::get('/failed-page', [PaymentController::class, 'failed'])->name('failed');

  Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
  Route::post('/create/{order}', [PaymentController::class, 'create'])->name('create');
  Route::post('/{payment}/check-status', [PaymentController::class, 'checkStatus'])->name('check-status');
});