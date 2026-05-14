<?php

use App\Models\Produk;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PenghasilanController;
use App\Http\Controllers\Admin\PengeluaranController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AIController;
use App\Http\Controllers\Admin\SearchController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Public Routes — Landing Page (UNTUK USER YANG BELUM LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('welcome'))->name('home');
Route::get('/service', fn() => view('user.service'))->name('service');
Route::get('/about', fn() => view('user.about'))->name('about');
Route::get('/shop', function (Request $request) {
    $query = Produk::query();
    if ($kategori = $request->get('kategori')) {
        $query->where('kategori', $kategori);
    }
    $produk = $query->orderBy('nama')->get();
    return view('user.shop', compact('produk'));
})->name('shop');
Route::get('/contact', fn() => view('user.contact'))->name('contact');

/*
|--------------------------------------------------------------------------
| Admin Routes - Hanya untuk admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/refresh-chart', [DashboardController::class, 'refreshChart'])->name('refresh-chart');
    Route::get('/notifications', [DashboardController::class, 'notifications'])->name('notifications');
    
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
        Route::get('/', [ProdukController::class, 'index'])->name('index');
        Route::get('/create', [ProdukController::class, 'create'])->name('create');
        Route::post('/', [ProdukController::class, 'store'])->name('store');
        Route::get('/{id}', [ProdukController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProdukController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProdukController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProdukController::class, 'destroy'])->name('destroy');
        Route::get('/export/pdf', [ProdukController::class, 'exportPDF'])->name('export.pdf');
    });

    Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik');
    Route::get('/statistik/export/pdf', [StatistikController::class, 'exportPDF'])->name('statistik.export.pdf');
    Route::get('/ai', [AIController::class, 'index'])->name('ai');
    Route::get('/ai/refresh', [AIController::class, 'refreshData'])->name('ai.refresh');
    Route::get('/ai/restock', [AIController::class, 'restockList'])->name('ai.restock');
    Route::post('/ai/apply-strategy', [AIController::class, 'applyStrategy'])->name('ai.apply-strategy');

    Route::get('/search', [SearchController::class, 'searchPage'])->name('search');
    Route::get('/search/api', [SearchController::class, 'search'])->name('search.api');
});

/*
|--------------------------------------------------------------------------
| User Routes - Hanya untuk user yang sudah login
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->name('user.')->group(function () {
    // Dashboard user
    Route::get('/dashboard', fn() => view('user.dashboard'))->name('dashboard');
    
    // TAMBAHKAN ROUTE INI:
    Route::get('/service', fn() => view('user.service'))->name('service');
    Route::get('/about', fn() => view('user.about'))->name('about');
    Route::get('/contact', fn() => view('user.contact'))->name('contact');
    
    // Route shop dengan data dari database
    Route::get('/shop', function (Request $request) {
        $query = Produk::query();
        if ($kategori = $request->get('kategori')) {
            $query->where('kategori', $kategori);
        }
        $produk = $query->orderBy('nama')->get();
        return view('user.shop', compact('produk'));
    })->name('shop');
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