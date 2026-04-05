<?php

use App\Http\Controllers\PendudukController;
use App\Livewire\Penduduk\Create;
use App\Livewire\Penduduk\Edit;
use App\Livewire\Penduduk\Index;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('penduduk.index'));

Route::get('/penduduk', Index::class)->name('penduduk.index');
Route::get('/penduduk/create', Create::class)->name('penduduk.create');
Route::get('/penduduk/{penduduk}/edit', Edit::class)->name('penduduk.edit');

Route::get('/penduduk/export/{format}', [PendudukController::class, 'export'])
  ->name('penduduk.export')
  ->where('format', 'csv|xls|xlsx');
