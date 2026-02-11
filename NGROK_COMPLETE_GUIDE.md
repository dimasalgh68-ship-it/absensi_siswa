# 🚀 Panduan Lengkap Ngrok - CSS & Gambar

## ✅ Semua Sudah Diperbaiki!

### Yang Sudah Dilakukan:
1. ✅ Build production assets (`npm run build`)
2. ✅ Perbaiki symbolic link storage
3. ✅ Update middleware untuk handle Storage::url() dinamis
4. ✅ Clear semua cache

## 🎯 Cara Menjalankan Ngrok (3 Langkah)

### 1. Start Laravel Server
```bash
php artisan serve
```

### 2. Start Ngrok (Terminal Baru)
```bash
ngrok http 8000
```

### 3. Buka di Browser
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

### CSS Tidak Muncul
```bash
npm run build
php artisan optimize:clear
# Restart server & ngrok
# Hard refresh browser (Ctrl+F5)
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

- [ ] `npm run build` sudah dijalankan
- [ ] File ada di `public/build/assets`
- [ ] `php artisan storage:link` sudah dijalankan
- [ ] `public/storage` link ke `storage/app/public`
- [ ] Cache sudah di-clear
- [ ] Laravel server running
- [ ] Ngrok running
- [ ] Browser hard refresh

## 🎓 Penjelasan Teknis

### Kenapa CSS Tidak Muncul?
- `npm run dev` menjalankan Vite di `localhost:5173`
- Port ini tidak bisa diakses dari luar (ngrok)
- Solusi: `npm run build` untuk production

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
# Build & prepare
npm run build && php artisan storage:link && php artisan optimize:clear

# Start server
php artisan serve

# Start ngrok (terminal baru)
ngrok http 8000
```

## 💡 Tips

1. **Development Lokal**: Gunakan `npm run dev`
2. **Testing Ngrok**: Gunakan `npm run build`
3. **Selalu hard refresh** browser setelah perubahan (Ctrl+F5)
4. **Cek F12 Console** untuk error
5. **Cek F12 Network** untuk status request

---

**Status:** ✅ Ready for ngrok with CSS & Images!

**Last Updated:** 2026-02-11
