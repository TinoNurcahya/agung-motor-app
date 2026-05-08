<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes — Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/',          fn() => view('welcome'))->name('home');
Route::get('/service',   fn() => view('user.service'))->name('service');
Route::get('/about',     fn() => view('user.about'))->name('about');
Route::get('/shop',      fn() => view('user.shop'))->name('shop');
Route::get('/contact',   fn() => view('user.contact'))->name('contact');

/*
|--------------------------------------------------------------------------
| Auth-Protected Routes — Admin Panel
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',            fn() => view('admin.dashboard.index'))->name('index');
    
    Route::prefix('penghasilan')->name('penghasilan.')->group(function() {
        Route::get('/',           fn() => view('admin.penghasilan.index'))->name('index');
        Route::get('/create',     fn() => view('admin.penghasilan.create'))->name('create');
        Route::get('/{id}/edit',  fn($id) => view('admin.penghasilan.edit'))->name('edit');
    });

    Route::prefix('pengeluaran')->name('pengeluaran.')->group(function() {
        Route::get('/',           fn() => view('admin.pengeluaran.index'))->name('index');
        Route::get('/create',     fn() => view('admin.pengeluaran.create'))->name('create');
        Route::get('/{id}/edit',  fn($id) => view('admin.pengeluaran.edit'))->name('edit');
    });

    Route::prefix('produk')->name('produk.')->group(function() {
        Route::get('/',           fn() => view('admin.produk.index'))->name('index');
        Route::get('/create',     fn() => view('admin.produk.create'))->name('create');
        Route::get('/{id}/edit',  fn($id) => view('admin.produk.edit'))->name('edit');
    });

    Route::get('/statistik',   fn() => view('admin.statistik.index'))->name('statistik');
    Route::get('/ai',          fn() => view('admin.ai.index'))->name('ai');
});

Route::get('/dashboard', fn() => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

