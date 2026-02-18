# Script untuk mempersiapkan aplikasi untuk ngrok
# Jalankan: .\prepare-ngrok.ps1

Write-Host "🚀 Mempersiapkan aplikasi untuk ngrok..." -ForegroundColor Cyan
Write-Host ""

# 1. Stop npm run dev jika masih berjalan
Write-Host "1️⃣  Menghentikan npm run dev..." -ForegroundColor Yellow
Stop-Process -Name node -Force -ErrorAction SilentlyContinue
Start-Sleep -Seconds 1
Write-Host "   ✅ Done" -ForegroundColor Green
Write-Host ""

# 2. Hapus file hot jika ada
Write-Host "2️⃣  Menghapus file hot..." -ForegroundColor Yellow
if (Test-Path "public\hot") {
    Remove-Item "public\hot" -Force
    Write-Host "   ✅ File hot dihapus" -ForegroundColor Green
} else {
    Write-Host "   ✅ File hot tidak ada (OK)" -ForegroundColor Green
}
Write-Host ""

# 3. Build production assets
Write-Host "3️⃣  Building production assets..." -ForegroundColor Yellow
npm run build
if ($LASTEXITCODE -eq 0) {
    Write-Host "   ✅ Build berhasil" -ForegroundColor Green
} else {
    Write-Host "   ❌ Build gagal" -ForegroundColor Red
    exit 1
}
Write-Host ""

# 4. Verifikasi build files
Write-Host "4️⃣  Verifikasi build files..." -ForegroundColor Yellow
if (Test-Path "public\build\manifest.json") {
    Write-Host "   ✅ manifest.json ada" -ForegroundColor Green
} else {
    Write-Host "   ❌ manifest.json tidak ada" -ForegroundColor Red
    exit 1
}

if (Test-Path "public\build\assets") {
    $cssFiles = Get-ChildItem "public\build\assets\*.css" -ErrorAction SilentlyContinue
    $jsFiles = Get-ChildItem "public\build\assets\*.js" -ErrorAction SilentlyContinue
    
    if ($cssFiles.Count -gt 0 -and $jsFiles.Count -gt 0) {
        Write-Host "   ✅ CSS dan JS files ada" -ForegroundColor Green
    } else {
        Write-Host "   ❌ CSS atau JS files tidak ada" -ForegroundColor Red
        exit 1
    }
} else {
    Write-Host "   ❌ Folder assets tidak ada" -ForegroundColor Red
    exit 1
}
Write-Host ""

# 5. Cek storage link
Write-Host "5️⃣  Verifikasi storage link..." -ForegroundColor Yellow
if (Test-Path "public\storage") {
    $link = Get-Item "public\storage"
    if ($link.LinkType -eq "Junction") {
        Write-Host "   ✅ Storage link ada" -ForegroundColor Green
    } else {
        Write-Host "   ⚠️  Storage bukan symbolic link, membuat ulang..." -ForegroundColor Yellow
        Remove-Item "public\storage" -Force -Recurse
        php artisan storage:link
        Write-Host "   ✅ Storage link dibuat" -ForegroundColor Green
    }
} else {
    Write-Host "   ⚠️  Storage link tidak ada, membuat..." -ForegroundColor Yellow
    php artisan storage:link
    Write-Host "   ✅ Storage link dibuat" -ForegroundColor Green
}
Write-Host ""

# 6. Clear cache
Write-Host "6️⃣  Clearing cache..." -ForegroundColor Yellow
php artisan optimize:clear | Out-Null
Write-Host "   ✅ Cache cleared" -ForegroundColor Green
Write-Host ""

# Summary
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host "✨ Persiapan selesai!" -ForegroundColor Green
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host ""
Write-Host "Langkah selanjutnya:" -ForegroundColor White
Write-Host "1. Jalankan: " -NoNewline -ForegroundColor White
Write-Host "php artisan serve" -ForegroundColor Yellow
Write-Host "2. Di terminal baru, jalankan: " -NoNewline -ForegroundColor White
Write-Host "ngrok http 8000" -ForegroundColor Yellow
Write-Host "3. Buka URL ngrok di browser" -ForegroundColor White
Write-Host "4. Tekan Ctrl+F5 untuk hard refresh" -ForegroundColor White
Write-Host ""
Write-Host "⚠️  JANGAN jalankan 'npm run dev' saat menggunakan ngrok!" -ForegroundColor Red
Write-Host ""
