# 🚀 Ngrok CSS & Gambar Fix - Panduan Cepat

## ✅ Sudah Diperbaiki!

Build production dan storage link sudah berhasil dibuat. Sekarang ikuti langkah berikut:

## Langkah-Langkah (5 Menit)

### 1. Pastikan Laravel Server Berjalan
```bash
# Jika menggunakan php artisan serve
php artisan serve

# Atau jika menggunakan Laragon, pastikan Apache/Nginx sudah running
```

### 2. Jalankan Ngrok
```bash
# Untuk php artisan serve (port 8000)
ngrok http 8000

# Untuk Laragon (port 80)
ngrok http 80
```

### 3. Buka URL Ngrok
- Copy URL dari terminal ngrok (contoh: `https://xxxx-xxx-xxx-xxx.ngrok-free.app`)
- Buka di browser
- Tekan **Ctrl + F5** untuk hard refresh

## ✨ Selesai!

CSS dan gambar sekarang sudah muncul karena:
- ✅ Assets sudah di-build untuk production
- ✅ Cache Laravel sudah dibersihkan
- ✅ Middleware SetDynamicAppUrl sudah aktif (termasuk fix untuk Storage::url)
- ✅ TrustProxies sudah dikonfigurasi
- ✅ Symbolic link storage sudah diperbaiki

## 🔧 Jika Masih Bermasalah

### Cek 1: Browser Console (F12)
Lihat apakah ada error di tab Console

### Cek 2: Hard Refresh
```
Ctrl + Shift + R  (Chrome/Firefox)
Ctrl + F5         (Windows)
```

### Cek 3: Coba Browser Lain
Test di Chrome Incognito atau browser lain

### Cek 4: Restart Semua
```bash
# Stop ngrok (Ctrl+C)
# Stop Laravel server (Ctrl+C)

# Jalankan ulang
php artisan serve
ngrok http 8000
```

## 📝 Catatan Penting

⚠️ **JANGAN gunakan `npm run dev` dengan ngrok!**

Selalu gunakan `npm run build` sebelum testing dengan ngrok.

## 🎯 Workflow yang Benar

```bash
# 1. Development lokal
npm run dev
php artisan serve
# Akses: http://localhost:8000

# 2. Testing dengan ngrok
npm run build              # Build assets
php artisan optimize:clear # Clear cache
php artisan serve          # Start server
ngrok http 8000           # Start ngrok
# Akses: https://xxxx.ngrok-free.app
```

## 🆘 Troubleshooting Cepat

| Masalah | Solusi |
|---------|--------|
| CSS tidak muncul | `npm run build` + hard refresh (Ctrl+F5) |
| Gambar tidak muncul | `php artisan storage:link` + restart server |
| Error 404 CSS | Cek folder `public/build/assets` ada file CSS |
| Error 404 gambar | Cek `public/storage` link ke `storage/app/public` |
| Mixed content | Middleware sudah handle otomatis |
| Blank page | Clear cache: `php artisan optimize:clear` |

## 📞 Command Reference

```bash
# Build production assets
npm run build

# Clear all cache
php artisan optimize:clear

# Fix storage link (jika gambar tidak muncul)
php artisan storage:link

# Start Laravel (port 8000)
php artisan serve

# Start ngrok
ngrok http 8000

# Cek build files
dir public\build\assets

# Cek storage link
Get-Item public\storage
```

---

**Status:** ✅ Ready to use with ngrok!
