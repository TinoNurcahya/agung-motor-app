<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PenghasilanController;
use App\Http\Controllers\Admin\PengeluaranController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes — Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('welcome'))->name('home');
Route::get('/service', fn() => view('user.service'))->name('service');
Route::get('/about', fn() => view('user.about'))->name('about');
Route::get('/shop', fn() => view('user.shop'))->name('shop');
Route::get('/contact', fn() => view('user.contact'))->name('contact');

/*
|--------------------------------------------------------------------------
| Admin Routes - Hanya untuk admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => view('admin.dashboard.index'))->name('index');
    
    Route::prefix('penghasilan')->name('penghasilan.')->group(function() {
        Route::get('/', [PenghasilanController::class, 'index'])->name('index');
        Route::get('/create', [PenghasilanController::class, 'create'])->name('create');
        Route::post('/', [PenghasilanController::class, 'store'])->name('store');
        Route::get('/{id}', [PenghasilanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PenghasilanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PenghasilanController::class, 'update'])->name('update');
        Route::delete('/{id}', [PenghasilanController::class, 'destroy'])->name('destroy');
        Route::get('/export/excel', [PenghasilanController::class, 'export'])->name('export');
        Route::get('/export/pdf', [PenghasilanController::class, 'exportPDF'])->name('export.pdf'); 
    });

    Route::prefix('pengeluaran')->name('pengeluaran.')->group(function() {
        Route::get('/', [PengeluaranController::class, 'index'])->name('index');
        Route::get('/create', [PengeluaranController::class, 'create'])->name('create');
        Route::post('/', [PengeluaranController::class, 'store'])->name('store');
        Route::get('/{id}', [PengeluaranController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PengeluaranController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PengeluaranController::class, 'update'])->name('update');
        Route::delete('/{id}', [PengeluaranController::class, 'destroy'])->name('destroy');
        Route::get('/export/excel', [PengeluaranController::class, 'export'])->name('export');
        Route::get('/export/pdf', [PengeluaranController::class, 'exportPDF'])->name('export.pdf');
    });

    Route::prefix('produk')->name('produk.')->group(function() {
        Route::get('/', fn() => view('admin.produk.index'))->name('index');
        Route::get('/create', fn() => view('admin.produk.create'))->name('create');
        Route::get('/{id}/edit', fn($id) => view('admin.produk.edit'))->name('edit');
    });

    Route::get('/statistik', fn() => view('admin.statistik.index'))->name('statistik');
    Route::get('/ai', fn() => view('admin.ai.index'))->name('ai');
});

/*
|--------------------------------------------------------------------------
| User Routes (opsional, jika ada halaman khusus user)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->name('user.')->group(function () {
    // Tambahkan route khusus user di sini
    Route::get('/dashboard', fn() => view('user.dashboard'))->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Profile Routes (bisa diakses semua role yang login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';