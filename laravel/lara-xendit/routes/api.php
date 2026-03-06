<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhook/xendit', [WebhookController::class, 'xendit'])->name('webhook.xendit');
