# 🚀 Ngrok Quick Start

## Masalah: CSS/Gambar Tidak Muncul di Ngrok?

### Solusi Tercepat (1 Command)

```powershell
.\prepare-ngrok.ps1
```

Lalu:
```bash
php artisan serve
ngrok http 8000  # di terminal baru
```

Buka URL ngrok di browser dan tekan **Ctrl+F5**

---

## Solusi Manual

```bash
# 1. Stop npm run dev
Stop-Process -Name node -Force

# 2. Hapus file hot
Remove-Item public\hot -Force

# 3. Build production
npm run build

# 4. Clear cache
php artisan optimize:clear

# 5. Start server
php artisan serve

# 6. Start ngrok (terminal baru)
ngrok http 8000
```

---

## Troubleshooting

| Masalah | Solusi |
|---------|--------|
| CSS tidak muncul | Jalankan `.\prepare-ngrok.ps1` |
| Gambar tidak muncul | `php artisan storage:link` |
| Masih error | Hard refresh browser (Ctrl+F5) |

---

## Dokumentasi Lengkap

- 📘 `NGROK_COMPLETE_GUIDE.md` - Panduan lengkap
- 🔧 `NGROK_QUICK_FIX.md` - Quick fix
- 🖼️ `NGROK_IMAGE_FIX.md` - Fix gambar
- 📝 `NGROK_CSS_TROUBLESHOOTING.md` - Troubleshooting CSS

---

## ⚠️ PENTING

**JANGAN jalankan `npm run dev` saat menggunakan ngrok!**

Selalu gunakan `npm run build` untuk ngrok.

---

**Status:** ✅ Ready!
