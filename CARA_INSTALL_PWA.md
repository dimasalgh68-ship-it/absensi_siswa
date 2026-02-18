# 📱 Cara Install Aplikasi Absensi (PWA)

## Apa itu PWA?
PWA (Progressive Web App) memungkinkan Anda menginstall aplikasi web ini ke perangkat mobile seperti aplikasi native. Setelah diinstall, Anda bisa:
- ✅ Akses aplikasi dari home screen (seperti app biasa)
- ✅ Buka tanpa perlu browser
- ✅ Akses lebih cepat
- ✅ Bekerja offline (sebagian fitur)
- ✅ Notifikasi push (coming soon)

---

## 📱 Install di Android (Chrome/Edge)

### Metode 1: Banner Install Otomatis (Paling Mudah)

1. **Buka aplikasi di Chrome/Edge**
   ```
   https://your-domain.com
   atau
   https://your-ngrok-url.ngrok.io
   ```

2. **Banner install akan muncul otomatis di bagian bawah**
   ```
   ┌─────────────────────────────────────┐
   │  📱  Install Aplikasi               │
   │      Akses lebih cepat dari         │
   │      home screen                    │
   │                                     │
   │  [Install]  [×]                     │
   └─────────────────────────────────────┘
   ```

3. **Klik tombol "Install"**

4. **Konfirmasi install**
   - Nama: Sistem Absensi Siswa
   - Klik "Install" atau "Tambahkan"

5. **Selesai!** 🎉
   - Icon aplikasi muncul di home screen
   - Buka dari home screen seperti app biasa

### Metode 2: Menu Browser

1. **Buka aplikasi di Chrome**

2. **Tap menu (⋮) di pojok kanan atas**

3. **Pilih salah satu:**
   - "Install app" atau
   - "Add to Home screen" atau
   - "Tambahkan ke layar utama"

4. **Edit nama jika perlu**
   - Default: "Absensi"
   - Bisa diganti sesuai keinginan

5. **Tap "Add" atau "Tambahkan"**

6. **Selesai!** 🎉

### Screenshot Android:
```
┌─────────────────────────┐
│  Chrome Menu (⋮)        │
├─────────────────────────┤
│  New tab                │
│  New incognito tab      │
│  Bookmarks              │
│  Recent tabs            │
│  History                │
│  Downloads              │
│  Share                  │
│  ✨ Install app         │  ← Klik ini!
│  Settings               │
└─────────────────────────┘
```

---

## 🍎 Install di iPhone/iPad (Safari)

### Langkah-langkah:

1. **Buka aplikasi di Safari**
   ```
   https://your-domain.com
   ```
   ⚠️ Harus Safari, tidak bisa Chrome/Firefox di iOS

2. **Tap tombol Share (kotak dengan panah ke atas)**
   - Lokasi: Di bagian bawah (iPhone) atau atas (iPad)
   ```
   ┌─────────────────────────┐
   │  Safari                 │
   │  ┌─────────────────┐    │
   │  │   Your Site     │    │
   │  └─────────────────┘    │
   │                         │
   │  [<] [>] [📤] [📖] [⋯] │  ← Tap icon 📤
   └─────────────────────────┘
   ```

3. **Scroll ke bawah dan tap "Add to Home Screen"**
   ```
   Share Menu:
   ┌─────────────────────────┐
   │  AirDrop                │
   │  Messages               │
   │  Mail                   │
   ├─────────────────────────┤
   │  Copy                   │
   │  ✨ Add to Home Screen  │  ← Tap ini!
   │  Add Bookmark           │
   │  Add to Reading List    │
   └─────────────────────────┘
   ```

4. **Edit nama jika perlu**
   - Default: "Absensi"
   - Bisa diganti

5. **Tap "Add" di pojok kanan atas**

6. **Selesai!** 🎉
   - Icon muncul di home screen
   - Buka seperti app biasa

---

## 💻 Install di Desktop (Windows/Mac/Linux)

### Chrome/Edge:

1. **Buka aplikasi di browser**

2. **Klik icon install di address bar**
   ```
   ┌────────────────────────────────────┐
   │  🔒 your-domain.com  [⊕]  ⋮       │  ← Klik [⊕]
   └────────────────────────────────────┘
   ```

3. **Atau: Menu (⋮) → "Install [App Name]"**

4. **Klik "Install"**

5. **Aplikasi terbuka di window terpisah**
   - Tanpa address bar
   - Seperti aplikasi desktop

6. **Shortcut otomatis dibuat di:**
   - Windows: Start Menu & Desktop
   - Mac: Applications folder
   - Linux: App drawer

---

## ✅ Cara Mengecek Sudah Terinstall

### Android:
1. Cek home screen → ada icon "Absensi"
2. Buka app drawer → cari "Absensi"
3. Settings → Apps → cari "Absensi"

### iOS:
1. Cek home screen → ada icon "Absensi"
2. Swipe ke library → cari "Absensi"

### Desktop:
1. Windows: Start Menu → cari "Absensi"
2. Mac: Applications → cari "Absensi"
3. Linux: App drawer → cari "Absensi"

---

## 🚀 Cara Menggunakan Setelah Install

### 1. Buka dari Home Screen
- Tap icon "Absensi" di home screen
- Aplikasi terbuka fullscreen (tanpa browser UI)
- Lebih cepat dari buka browser

### 2. Quick Access ke Face Scan
**Android:**
- Long press icon "Absensi"
- Pilih "Face Scan" dari shortcuts
- Langsung ke halaman scan

**iOS:**
- 3D Touch/Haptic Touch icon "Absensi"
- Pilih "Face Scan"

### 3. Offline Mode
- Aplikasi tetap bisa dibuka saat offline
- Beberapa halaman di-cache
- Attendance akan di-sync saat online kembali

---

## 🔧 Troubleshooting

### Banner Install Tidak Muncul

**Penyebab:**
- Aplikasi sudah terinstall
- Tidak menggunakan HTTPS
- Browser tidak support PWA
- User sudah dismiss banner 3x

**Solusi:**
1. Gunakan Metode 2 (Menu Browser)
2. Pastikan menggunakan HTTPS (ngrok)
3. Coba di browser lain
4. Clear browser data dan coba lagi

### Icon Tidak Muncul di Home Screen

**Penyebab:**
- Install gagal
- Permission ditolak

**Solusi:**
1. Uninstall dan install ulang
2. Restart device
3. Cek storage space

### Aplikasi Tidak Buka

**Penyebab:**
- Service worker error
- Cache corrupt

**Solusi:**
1. Uninstall aplikasi
2. Clear browser cache
3. Install ulang

### Cara Uninstall

**Android:**
1. Long press icon "Absensi"
2. Tap "Uninstall" atau "App info" → "Uninstall"

**iOS:**
1. Long press icon "Absensi"
2. Tap "Remove App" → "Delete App"

**Desktop:**
1. Chrome: chrome://apps → Right click → "Remove"
2. Windows: Settings → Apps → Uninstall
3. Mac: Applications → Drag to Trash

---

## 📊 Keuntungan Install PWA

### Kecepatan
- ⚡ Load 2-3x lebih cepat
- 📦 Assets di-cache
- 🚀 Instant loading

### User Experience
- 📱 Fullscreen (no browser UI)
- 🎨 Custom splash screen
- 🔔 Push notifications (soon)
- 📍 Better GPS access

### Offline Support
- 📡 Bekerja tanpa internet (sebagian)
- 💾 Data di-cache
- 🔄 Auto sync saat online

### Storage
- 💿 Minimal storage (~5-10 MB)
- 🗑️ Easy to uninstall
- 🔄 Auto update

---

## 🎯 Tips & Tricks

### 1. Shortcut Keyboard (Desktop)
- `Ctrl/Cmd + R` - Reload
- `Ctrl/Cmd + W` - Close
- `F11` - Fullscreen

### 2. Update Aplikasi
- Update otomatis saat buka app
- Notifikasi jika ada update
- Reload untuk apply update

### 3. Clear Cache
**Android:**
- Settings → Apps → Absensi → Storage → Clear cache

**iOS:**
- Uninstall dan install ulang

**Desktop:**
- DevTools (F12) → Application → Clear storage

### 4. Check Update Manual
- Buka app
- Pull to refresh (mobile)
- Atau reload page

---

## 📞 Butuh Bantuan?

### Jika mengalami masalah:
1. Screenshot error yang muncul
2. Catat langkah yang sudah dilakukan
3. Hubungi admin/developer
4. Atau buka issue di GitHub

### Informasi yang perlu disertakan:
- Device: (Android/iOS/Desktop)
- Browser: (Chrome/Safari/Edge)
- OS Version: (Android 12, iOS 16, Windows 11, dll)
- Screenshot error

---

## 🎉 Selamat!

Anda sekarang bisa menggunakan aplikasi absensi seperti aplikasi native!

**Fitur yang bisa digunakan:**
- ✅ Face Scan untuk absensi
- ✅ Lihat riwayat absensi
- ✅ Daftar wajah untuk face recognition
- ✅ Notifikasi (coming soon)
- ✅ Offline mode (sebagian)

**Happy scanning! 📸**

---

**Dibuat**: 11 Februari 2026
**Update**: Sesuai kebutuhan
