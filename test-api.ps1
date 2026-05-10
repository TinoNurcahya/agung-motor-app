# test-api-fixed.ps1
$BASE_URL = "http://localhost:8000/api"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "   TESTING API AGUNG MOTOR" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# 1. LOGIN
Write-Host "1. TEST LOGIN" -ForegroundColor Yellow
$loginBody = '{"email":"admin@test.com","password":"password123"}'

try {
    $response = Invoke-RestMethod -Uri "$BASE_URL/login" `
        -Method Post `
        -Body $loginBody `
        -ContentType "application/json"
    
    $token = $response.data.token
    Write-Host "   Login Berhasil!" -ForegroundColor Green
    Write-Host "   Token: $token" -ForegroundColor Gray
} catch {
    Write-Host "   Login Gagal: $($_.Exception.Message)" -ForegroundColor Red
    exit
}

Write-Host ""

# 2. DASHBOARD
Write-Host "2. TEST DASHBOARD" -ForegroundColor Yellow
$headers = @{"Authorization" = "Bearer $token"}

try {
    $dashboard = Invoke-RestMethod -Uri "$BASE_URL/dashboard/overview?period=30" -Method Get -Headers $headers
    Write-Host "   Dashboard Berhasil!" -ForegroundColor Green
    Write-Host "   Total Penghasilan: $($dashboard.data.total_penghasilan)" -ForegroundColor Gray
    Write-Host "   Total Pengeluaran: $($dashboard.data.total_pengeluaran)" -ForegroundColor Gray
    Write-Host "   Laba Bersih: $($dashboard.data.laba_bersih)" -ForegroundColor Gray
} catch {
    Write-Host "   Dashboard Gagal: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# 3. PRODUK
Write-Host "3. TEST PRODUK" -ForegroundColor Yellow
try {
    $produk = Invoke-RestMethod -Uri "$BASE_URL/produk" -Method Get -Headers $headers
    Write-Host "   Produk Berhasil!" -ForegroundColor Green
    Write-Host "   Total Produk: $($produk.summary.total_produk)" -ForegroundColor Gray
    Write-Host "   Low Stock: $($produk.summary.low_stock)" -ForegroundColor Gray
} catch {
    Write-Host "   Produk Gagal: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# 4. AI PREDICTIONS
Write-Host "4. TEST AI PREDICTIONS" -ForegroundColor Yellow
try {
    $ai = Invoke-RestMethod -Uri "$BASE_URL/ai/predictions?period=30" -Method Get -Headers $headers
    Write-Host "   AI Predictions Berhasil!" -ForegroundColor Green
    Write-Host "   Predicted Revenue: $($ai.data.revenue_prediction.predicted_revenue_formatted)" -ForegroundColor Gray
} catch {
    Write-Host "   AI Predictions Gagal: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# 5. SEARCH
Write-Host "5. TEST SEARCH" -ForegroundColor Yellow
try {
    $search = Invoke-RestMethod -Uri "$BASE_URL/search?q=oli" -Method Get -Headers $headers
    Write-Host "   Search Berhasil!" -ForegroundColor Green
    Write-Host "   Total Hasil: $($search.total)" -ForegroundColor Gray
} catch {
    Write-Host "   Search Gagal: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# 6. LOGOUT
Write-Host "6. TEST LOGOUT" -ForegroundColor Yellow
try {
    Invoke-RestMethod -Uri "$BASE_URL/logout" -Method Post -Headers $headers
    Write-Host "   Logout Berhasil!" -ForegroundColor Green
} catch {
    Write-Host "   Logout Gagal: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "   TESTING SELESAI" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan