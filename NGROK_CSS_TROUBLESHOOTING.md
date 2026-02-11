# Ngrok CSS Troubleshooting Guide

## Problem: CSS Tidak Muncul di Ngrok

### Penyebab Utama
CSS tidak muncul karena menggunakan `npm run dev` yang menjalankan Vite dev server di `localhost:5173`. Port ini tidak bisa diakses dari luar (ngrok).

### Solusi Cepat ⚡
```bash
# 1. Build assets untuk production
npm run build

# 2. Clear cache Laravel
php artisan optimize:clear

# 3. Restart ngrok
# Ctrl+C untuk stop, lalu jalankan lagi
ngrok http 8000
```

## Langkah-Langkah Detail

### 1. Build Assets Production
```bash
npm run build
```
Output yang benar:
```
✓ 53 modules transformed.
public/build/manifest.json              0.26 kB
public/build/assets/app-d3e0a824.css  122.30 kB
public/build/assets/app-2530347a.js    36.85 kB
✓ built in 2.52s
```

### 2. Verifikasi File Build
Cek folder `public/build`:
```bash
dir public\build
```
Harus ada:
- `manifest.json`
- Folder `assets` dengan file CSS dan JS

### 3. Clear Cache
```bash
php artisan optimize:clear
php artisan config:clear
php artisan view:clear
```

### 4. Cek Konfigurasi .env
```env
APP_ENV=production
APP_DEBUG=false
```

### 5. Start Ngrok
```bash
# Untuk php artisan serve (port 8000)
ngrok http 8000

# Untuk Laragon (port 80)
ngrok http 80
```

### 6. Test di Browser
1. Buka URL ngrok (contoh: `https://xxxx.ngrok-free.app`)
2. Tekan F12 untuk buka Developer Tools
3. Cek tab Console untuk error
4. Cek tab Network untuk melihat request CSS

## Troubleshooting Lanjutan

### CSS Masih Tidak Muncul

#### Cek 1: Verifikasi Build Files
```bash
# Windows
dir public\build\assets

# Harus muncul file .css dan .js
```

#### Cek 2: Browser Console
Buka Developer Tools (F12) → Console
- Jika ada error "Mixed Content", middleware sudah handle otomatis
- Jika ada error 404, rebuild assets

#### Cek 3: Network Tab
Buka Developer Tools (F12) → Network
- Filter: CSS
- Refresh halaman (Ctrl+F5)
- Lihat status code file CSS (harus 200, bukan 404)

#### Cek 4: Clear Browser Cache
```
Ctrl + Shift + Delete
atau
Ctrl + F5 (hard refresh)
```

### Error: manifest.json not found

**Solusi:**
```bash
npm run build
```

### Error: Vite manifest not found

**Penyebab:** Masih menggunakan dev mode

**Solusi:**
1. Stop `npm run dev` jika sedang jalan
2. Jalankan `npm run build`
3. Restart Laravel server

### Mixed Content Warning

**Sudah Diatasi Otomatis** oleh middleware `SetDynamicAppUrl`

Middleware ini:
- Deteksi ngrok URL
- Force HTTPS untuk semua asset
- Set root URL dinamis

## Konfigurasi yang Sudah Benar

### File: bootstrap/app.php
```php
$middleware->trustProxies(at: '*');
$middleware->web(append: [
    \App\Http\Middleware\SetDynamicAppUrl::class,
]);
```

### File: app/Http/Middleware/SetDynamicAppUrl.php
```php
if (str_contains($request->header('Host', ''), 'ngrok')) {
    URL::forceScheme('https');
    // ... set root URL
}
```

## Checklist Sebelum Ngrok

- [ ] Jalankan `npm run build`
- [ ] Cek file di `public/build/assets`
- [ ] Set `APP_ENV=production` di `.env`
- [ ] Clear cache: `php artisan optimize:clear`
- [ ] Laravel server running
- [ ] Start ngrok
- [ ] Test di browser

## Command Reference

```bash
# Build production
npm run build

# Clear semua cache
php artisan optimize:clear

# Start Laravel server
php artisan serve

# Start ngrok (port 8000)
ngrok http 8000

# Start ngrok (port 80)
ngrok http 80

# Cek versi
php artisan --version
node --version
npm --version
```

## Tips Performance

1. **Gunakan production build** - Lebih cepat dan optimal
2. **Cache config** (opsional):
   ```bash
   php artisan config:cache
   php artisan route:cache
   ```
3. **Jangan gunakan dev mode** untuk ngrok

## Catatan Penting

⚠️ **JANGAN gunakan `npm run dev` dengan ngrok!**
- Vite dev server tidak bisa diakses dari luar
- Selalu gunakan `npm run build`

✅ **Workflow yang Benar:**
1. Development lokal → `npm run dev`
2. Testing dengan ngrok → `npm run build`
3. Deploy production → `npm run build`

## Jika Masih Bermasalah

1. **Restart semua**:
   ```bash
   # Stop semua (Ctrl+C)
   npm run build
   php artisan optimize:clear
   php artisan serve
   ngrok http 8000
   ```

2. **Cek log error**:
   ```bash
   # Laravel log
   type storage\logs\laravel.log
   ```

3. **Test di browser lain**:
   - Chrome
   - Firefox
   - Edge
   - Mode incognito

4. **Cek koneksi internet**:
   - Ngrok memerlukan koneksi internet
   - Pastikan tidak ada firewall yang block

## Kesimpulan

Masalah CSS di ngrok 99% diselesaikan dengan:
```bash
npm run build
php artisan optimize:clear
```

Lalu restart ngrok dan refresh browser dengan Ctrl+F5.
