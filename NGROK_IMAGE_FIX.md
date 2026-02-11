# 🖼️ Ngrok - Fix Gambar Tidak Muncul

## Masalah yang Diperbaiki

1. ✅ Symbolic link storage yang rusak/salah path
2. ✅ Storage::url() tidak dinamis untuk ngrok
3. ✅ Middleware SetDynamicAppUrl sudah di-update

## Apa yang Sudah Dilakukan?

### 1. Perbaikan Symbolic Link
```bash
# Hapus link lama yang salah
Remove-Item public\storage -Force

# Buat link baru yang benar
php artisan storage:link
```

**Hasil:**
```
public/storage → storage/app/public
```

### 2. Update Middleware SetDynamicAppUrl

Ditambahkan konfigurasi dinamis untuk filesystem:

```php
// Update filesystem disk URL untuk Storage::url()
config(['filesystems.disks.public.url' => $appUrl . '/storage']);
```

Sekarang `Storage::url()` akan otomatis menggunakan URL ngrok yang benar.

### 3. Clear Config Cache
```bash
php artisan config:clear
```

## Cara Kerja

### Sebelum Fix:
```
Storage::url('face-registrations/photo.jpg')
↓
http://localhost:8000/storage/face-registrations/photo.jpg  ❌ (salah URL)
```

### Setelah Fix:
```
Storage::url('face-registrations/photo.jpg')
↓
https://xxxx.ngrok-free.app/storage/face-registrations/photo.jpg  ✅ (URL ngrok)
```

## Verifikasi

### Cek Symbolic Link
```powershell
Get-Item public\storage | Select-Object LinkType, Target
```

**Output yang benar:**
```
LinkType Target
-------- ------
Junction {C:\laragon\www\absensi-siswa\storage\app\public}
```

### Cek Gambar Ada
```bash
dir storage\app\public\face-registrations
dir storage\app\public\profile-photos
dir storage\app\public\logos
```

### Test di Browser
1. Buka ngrok URL
2. F12 → Network tab
3. Filter: Img
4. Refresh halaman
5. Lihat status gambar (harus 200, bukan 404)

## Troubleshooting

### Gambar Masih 404

**Solusi 1: Recreate Storage Link**
```bash
# Hapus link
Remove-Item public\storage -Force

# Buat ulang
php artisan storage:link

# Restart server
# Ctrl+C lalu php artisan serve
```

**Solusi 2: Cek Permission**
```bash
# Pastikan folder storage bisa diakses
dir storage\app\public
```

**Solusi 3: Hard Refresh Browser**
```
Ctrl + Shift + R  (Chrome/Firefox)
Ctrl + F5         (Windows)
```

### Gambar Broken/Corrupt

**Cek path di database:**
```sql
-- Contoh untuk face_registrations
SELECT id, user_id, photo_path FROM face_registrations LIMIT 5;

-- Path yang benar: face-registrations/xxxxx.jpg
-- Bukan: storage/face-registrations/xxxxx.jpg
```

### Mixed Content Warning

Middleware sudah handle otomatis. Jika masih ada warning:

```bash
# Clear cache
php artisan config:clear
php artisan optimize:clear

# Restart server
```

## File yang Menggunakan Storage::url()

Gambar di aplikasi ini menggunakan `Storage::url()` di:

1. **Face Registration**
   - `resources/views/face-registration/index.blade.php`
   - `resources/views/livewire/admin/face-registration-table.blade.php`

2. **Profile Photos**
   - `resources/views/livewire/admin/face-registration-table.blade.php`

3. **Bills/Bukti Pembayaran**
   - `resources/views/livewire/user/bills-component.blade.php`
   - `resources/views/livewire/admin/bills-component.blade.php`

4. **Task Images**
   - `resources/views/livewire/user/task-list.blade.php`

5. **App Logo**
   - `resources/views/admin/settings.blade.php`

Semua sudah otomatis menggunakan URL ngrok yang benar setelah middleware di-update.

## Testing Checklist

- [ ] Symbolic link benar: `Get-Item public\storage`
- [ ] Gambar ada di storage: `dir storage\app\public`
- [ ] Config clear: `php artisan config:clear`
- [ ] Server restart
- [ ] Ngrok running
- [ ] Browser hard refresh (Ctrl+F5)
- [ ] F12 → Network → Img (status 200)

## Catatan Penting

⚠️ **Jangan commit folder `public/storage`**
- Ini adalah symbolic link, bukan folder asli
- Sudah ada di `.gitignore`

⚠️ **Setiap clone/pull baru, jalankan:**
```bash
php artisan storage:link
```

✅ **Gambar disimpan di:**
```
storage/app/public/
├── face-registrations/
├── face-verifications/
├── profile-photos/
├── logos/
├── task-submissions/
└── tasks/
```

✅ **Diakses via:**
```
public/storage/ → symbolic link → storage/app/public/
```

## Summary

Masalah gambar tidak muncul di ngrok sudah diperbaiki dengan:

1. ✅ Recreate symbolic link yang benar
2. ✅ Update middleware untuk handle Storage::url() dinamis
3. ✅ Clear config cache

Sekarang semua gambar (profile photo, face registration, bills, tasks, logo) akan muncul dengan benar di ngrok!

---

**Status:** ✅ Images working with ngrok!
