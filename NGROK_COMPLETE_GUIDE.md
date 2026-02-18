# 🚀 Panduan Lengkap Ngrok - CSS & Gambar

## ✅ Semua Sudah Diperbaiki!

### Yang Sudah Dilakukan:
1. ✅ Build production assets (`npm run build`)
2. ✅ Perbaiki symbolic link storage
3. ✅ Update middleware untuk handle Storage::url() dinamis
4. ✅ Clear semua cache

## 🎯 Cara Menjalankan Ngrok

### Opsi 1: Gunakan Script Otomatis (RECOMMENDED)
```powershell
.\prepare-ngrok.ps1
```
Script ini akan otomatis:
- Stop `npm run dev`
- Hapus file `hot`
- Build production assets
- Verifikasi semua file
- Clear cache

Lalu jalankan:
```bash
php artisan serve
ngrok http 8000  # di terminal baru
```

### Opsi 2: Manual (3 Langkah)

#### 1. Persiapan
```bash
# Stop npm run dev jika sedang berjalan (Ctrl+C)
# Hapus file hot
Remove-Item public\hot -Force -ErrorAction SilentlyContinue

# Build production
npm run build

# Clear cache
php artisan optimize:clear
```

#### 2. Start Laravel Server
```bash
php artisan serve
```

#### 3. Start Ngrok (Terminal Baru)
```bash
ngrok http 8000
```

#### 4. Buka di Browser
- Copy URL dari ngrok (contoh: `https://xxxx-xxx-xxx.ngrok-free.app`)
- Buka di browser
- Tekan **Ctrl + F5** (hard refresh)

## ✨ Selesai!

Sekarang:
- ✅ CSS muncul dengan sempurna
- ✅ Gambar profile muncul
- ✅ Gambar face registration muncul
- ✅ Gambar bukti pembayaran muncul
- ✅ Logo aplikasi muncul
- ✅ Semua asset menggunakan HTTPS ngrok

## 🔧 Jika Ada Masalah

### CSS Tidak Muncul (PALING SERING)

**Penyebab:** File `public/hot` masih ada atau `npm run dev` masih berjalan

**Solusi:**
```bash
# Stop npm run dev (Ctrl+C atau kill process)
Stop-Process -Name node -Force

# Hapus file hot
Remove-Item public\hot -Force

# Build ulang
npm run build

# Clear cache
php artisan optimize:clear

# Restart server & ngrok
# Hard refresh browser (Ctrl+F5)
```

**Atau gunakan script:**
```powershell
.\prepare-ngrok.ps1
```

### Gambar Tidak Muncul
```bash
php artisan storage:link
php artisan config:clear
# Restart server & ngrok
# Hard refresh browser (Ctrl+F5)
```

### Semua Tidak Muncul (Nuclear Option)
```bash
# Stop semua (Ctrl+C)
npm run build
php artisan storage:link
php artisan optimize:clear
php artisan serve
# Di terminal baru:
ngrok http 8000
# Browser: Ctrl+Shift+Delete (clear cache) atau buka Incognito
```

## 📋 Checklist Sebelum Ngrok

- [ ] `npm run dev` sudah di-stop (Ctrl+C)
- [ ] Tidak ada proses node: `Get-Process node`
- [ ] File `public/hot` tidak ada
- [ ] `npm run build` sudah dijalankan
- [ ] File ada di `public/build/assets`
- [ ] `php artisan storage:link` sudah dijalankan
- [ ] `public/storage` link ke `storage/app/public`
- [ ] Cache sudah di-clear: `php artisan optimize:clear`
- [ ] Laravel server running: `php artisan serve`
- [ ] Ngrok running: `ngrok http 8000`
- [ ] Browser hard refresh: Ctrl+F5

**Atau cukup jalankan:**
```powershell
.\prepare-ngrok.ps1
```

## 🎓 Penjelasan Teknis

### Kenapa CSS Tidak Muncul?

**Masalah 1: File `public/hot` Ada**
- Ketika `npm run dev` berjalan, Vite membuat file `public/hot`
- File ini memberitahu Laravel untuk menggunakan Vite dev server
- Vite dev server berjalan di `localhost:5173` yang tidak bisa diakses dari ngrok
- **Solusi:** Hapus file `public/hot` dan gunakan `npm run build`

**Masalah 2: npm run dev Masih Berjalan**
- Proses node masih berjalan di background
- Terus membuat file `public/hot`
- **Solusi:** Stop semua proses node: `Stop-Process -Name node -Force`

**Masalah 3: Cache**
- Laravel cache konfigurasi lama
- **Solusi:** `php artisan optimize:clear`

### Kenapa Gambar Tidak Muncul?
- Gambar disimpan di `storage/app/public`
- Perlu symbolic link ke `public/storage`
- `Storage::url()` perlu konfigurasi dinamis untuk ngrok
- Middleware `SetDynamicAppUrl` sudah handle ini

### Bagaimana Middleware Bekerja?
```php
// Deteksi ngrok
if (str_contains($request->header('Host'), 'ngrok')) {
    // Force HTTPS
    URL::forceScheme('https');
    
    // Set root URL dinamis
    URL::forceRootUrl('https://xxxx.ngrok-free.app');
    
    // Update config
    config(['app.url' => 'https://xxxx.ngrok-free.app']);
    
    // Fix Storage::url()
    config(['filesystems.disks.public.url' => 'https://xxxx.ngrok-free.app/storage']);
}
```

## 📁 Struktur File

```
public/
├── build/              # Production assets (CSS, JS)
│   ├── manifest.json
│   └── assets/
│       ├── app-xxx.css
│       └── app-xxx.js
└── storage/            # Symbolic link → storage/app/public

storage/
└── app/
    └── public/         # Gambar disimpan di sini
        ├── face-registrations/
        ├── face-verifications/
        ├── profile-photos/
        ├── logos/
        ├── task-submissions/
        └── tasks/
```

## 🔗 Dokumentasi Lengkap

- `NGROK_QUICK_FIX.md` - Panduan cepat
- `NGROK_IMAGE_FIX.md` - Detail fix gambar
- `NGROK_CSS_TROUBLESHOOTING.md` - Troubleshooting CSS

## ⚡ Quick Commands

```bash
# Cara TERCEPAT (gunakan script)
.\prepare-ngrok.ps1
php artisan serve
ngrok http 8000  # di terminal baru

# Atau manual
Stop-Process -Name node -Force
Remove-Item public\hot -Force
npm run build && php artisan storage:link && php artisan optimize:clear

# Start server
php artisan serve

# Start ngrok (terminal baru)
ngrok http 8000
```

## 💡 Tips

1. **Development Lokal**: Gunakan `npm run dev`
2. **Testing Ngrok**: Gunakan `npm run build` (JANGAN `npm run dev`)
3. **File `public/hot`**: Harus dihapus sebelum ngrok
4. **Selalu hard refresh** browser setelah perubahan (Ctrl+F5)
5. **Cek F12 Console** untuk error
6. **Cek F12 Network** untuk status request
7. **Gunakan script `prepare-ngrok.ps1`** untuk otomatis

## ⚠️ PENTING - Jangan Lupa!

❌ **JANGAN:**
- Jalankan `npm run dev` bersamaan dengan ngrok
- Lupa hapus file `public/hot`
- Lupa stop proses node sebelum build

✅ **LAKUKAN:**
- Stop `npm run dev` sebelum ngrok
- Hapus `public/hot` sebelum ngrok
- Gunakan `npm run build` untuk ngrok
- Hard refresh browser (Ctrl+F5)
- Gunakan script `prepare-ngrok.ps1`

---

**Status:** ✅ Ready for ngrok with CSS & Images!

**Last Updated:** 2026-02-11
