<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PenghasilanController;
use App\Http\Controllers\Api\PengeluaranController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\StatistikController;
use App\Http\Controllers\Api\AIController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SearchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes (authentication)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    
    // Dashboard
    Route::get('/dashboard/overview', [DashboardController::class, 'overview']);
    Route::get('/dashboard/chart', [DashboardController::class, 'chart']);
    
    // Penghasilan (Income)
    Route::apiResource('penghasilan', PenghasilanController::class);
    Route::get('/penghasilan/export/excel', [PenghasilanController::class, 'exportExcel']);
    Route::get('/penghasilan/export/pdf', [PenghasilanController::class, 'exportPDF']);
    Route::post('/penghasilan/bulk', [PenghasilanController::class, 'bulkStore']);
    
    // Pengeluaran (Expense)
    Route::apiResource('pengeluaran', PengeluaranController::class);
    Route::get('/pengeluaran/export/excel', [PengeluaranController::class, 'exportExcel']);
    Route::get('/pengeluaran/export/pdf', [PengeluaranController::class, 'exportPDF']);
    Route::post('/pengeluaran/bulk', [PengeluaranController::class, 'bulkStore']);
    
    // Produk (Products)
    Route::apiResource('produk', ProdukController::class);
    Route::get('/produk/export/pdf', [ProdukController::class, 'exportPDF']);
    Route::post('/produk/bulk', [ProdukController::class, 'bulkStore']);
    Route::put('/produk/{id}/stock', [ProdukController::class, 'updateStock']);
    
    // Statistik
    Route::get('/statistik/summary', [StatistikController::class, 'summary']);
    Route::get('/statistik/trend', [StatistikController::class, 'trend']);
    Route::get('/statistik/chart', [StatistikController::class, 'chart']);
    Route::get('/statistik/top-services', [StatistikController::class, 'topServices']);
    Route::get('/statistik/export/pdf', [StatistikController::class, 'exportPDF']);
    
    // AI Analytics
    Route::get('/ai/predictions', [AIController::class, 'predictions']);
    Route::get('/ai/stock-health', [AIController::class, 'stockHealth']);
    Route::get('/ai/customer-retention', [AIController::class, 'customerRetention']);
    Route::get('/ai/forecast', [AIController::class, 'forecast']);
    Route::get('/ai/recommendations', [AIController::class, 'recommendations']);
    Route::get('/ai/refresh', [AIController::class, 'refresh']);
    
    // Search
    Route::get('/search', [SearchController::class, 'search']);
    Route::get('/search/advanced', [SearchController::class, 'advancedSearch']);
});