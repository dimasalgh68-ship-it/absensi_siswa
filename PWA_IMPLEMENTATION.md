# Progressive Web App (PWA) Implementation

## Overview
Aplikasi Absensi Siswa sekarang mendukung PWA (Progressive Web App), memungkinkan user untuk menginstall aplikasi di perangkat mobile mereka seperti aplikasi native. Ini memberikan akses cepat ke fitur Face Scan tanpa harus membuka browser secara manual.

## Fitur PWA yang Diimplementasikan

### 1. Installable
✅ User dapat install aplikasi ke home screen
✅ Icon aplikasi muncul di home screen seperti app native
✅ Splash screen saat membuka aplikasi
✅ Fullscreen mode (tanpa address bar browser)

### 2. Offline Support
✅ Service Worker untuk caching assets
✅ Halaman offline ketika tidak ada koneksi
✅ Auto reload ketika koneksi kembali
✅ Background sync untuk attendance submissions

### 3. App Shortcuts
✅ Quick access ke Face Scan dari home screen
✅ Quick access ke Riwayat Absensi
✅ Quick access ke Registrasi Wajah

### 4. Push Notifications (Ready)
✅ Infrastructure siap untuk push notifications
✅ Notification click handler
✅ Badge dan vibration support

## File yang Dibuat

### 1. Manifest File
**File**: `public/manifest.json`

Berisi konfigurasi PWA:
- App name dan short name
- Icons (berbagai ukuran)
- Theme color dan background color
- Display mode (standalone)
- App shortcuts
- Categories dan screenshots

### 2. Service Worker
**File**: `public/sw.js`

Fitur:
- Cache static assets on install
- Network-first strategy untuk HTML pages
- Cache-first strategy untuk static assets
- Offline page fallback
- Background sync untuk attendance
- Push notification handler
- Auto update detection

### 3. Offline Page
**File**: `public/offline.html`

Halaman yang ditampilkan ketika:
- User offline dan mencoba akses halaman yang tidak di-cache
- Network request gagal
- Auto reload ketika koneksi kembali

### 4. PWA Icons Folder
**Folder**: `public/pwa-icons/`

Berisi icons berbagai ukuran:
- 72x72, 96x96, 128x128, 144x144
- 152x152, 192x192, 384x384, 512x512

### 5. Updated Layout
**File**: `resources/views/layouts/app.blade.php`

Ditambahkan:
- PWA meta tags
- Manifest link
- Apple touch icons
- Service worker registration
- Install prompt handler
- Online/offline detection

## Cara Generate PWA Icons

### Opsi 1: Online Tool (Recommended)
1. Buka https://www.pwabuilder.com/imageGenerator
2. Upload logo aplikasi (minimal 512x512px)
3. Download generated icons
4. Extract ke folder `public/pwa-icons/`

### Opsi 2: Manual dengan ImageMagick
```bash
# Install ImageMagick terlebih dahulu
# https://imagemagick.org/script/download.php

# Generate dari logo
magick convert favicon.png -resize 72x72 pwa-icons/icon-72x72.png
magick convert favicon.png -resize 96x96 pwa-icons/icon-96x96.png
magick convert favicon.png -resize 128x128 pwa-icons/icon-128x128.png
magick convert favicon.png -resize 144x144 pwa-icons/icon-144x144.png
magick convert favicon.png -resize 152x152 pwa-icons/icon-152x152.png
magick convert favicon.png -resize 192x192 pwa-icons/icon-192x192.png
magick convert favicon.png -resize 384x384 pwa-icons/icon-384x384.png
magick convert favicon.png -resize 512x512 pwa-icons/icon-512x512.png
```

### Opsi 3: Gunakan Logo yang Ada
Jika sudah ada logo di `public/favicon.png`, copy dan rename:
```bash
# Copy favicon.png ke semua ukuran (temporary)
copy public\favicon.png public\pwa-icons\icon-72x72.png
copy public\favicon.png public\pwa-icons\icon-96x96.png
copy public\favicon.png public\pwa-icons\icon-128x128.png
copy public\favicon.png public\pwa-icons\icon-144x144.png
copy public\favicon.png public\pwa-icons\icon-152x152.png
copy public\favicon.png public\pwa-icons\icon-192x192.png
copy public\favicon.png public\pwa-icons\icon-384x384.png
copy public\favicon.png public\pwa-icons\icon-512x512.png
```

## Cara Install PWA

### Android (Chrome/Edge)
1. Buka aplikasi di browser Chrome/Edge
2. Banner "Install Aplikasi" akan muncul otomatis
3. Klik tombol "Install"
4. Atau: Menu (⋮) → "Install app" / "Add to Home screen"
5. Icon aplikasi akan muncul di home screen

### iOS (Safari)
1. Buka aplikasi di Safari
2. Tap tombol Share (kotak dengan panah ke atas)
3. Scroll dan tap "Add to Home Screen"
4. Edit nama jika perlu
5. Tap "Add"
6. Icon aplikasi akan muncul di home screen

### Desktop (Chrome/Edge)
1. Buka aplikasi di browser
2. Klik icon install di address bar (⊕)
3. Atau: Menu (⋮) → "Install [App Name]"
4. Aplikasi akan terbuka di window terpisah

## Testing PWA

### 1. Test Manifest
```bash
# Akses manifest
http://localhost/manifest.json

# Pastikan response JSON valid
```

### 2. Test Service Worker
```bash
# Buka DevTools (F12)
# Tab: Application → Service Workers
# Pastikan service worker registered dan active
```

### 3. Test Offline Mode
```bash
# Buka DevTools (F12)
# Tab: Network → Throttling → Offline
# Refresh halaman
# Pastikan offline page muncul
```

### 4. Test Install Prompt
```bash
# Buka di Chrome/Edge
# Pastikan banner install muncul
# Atau cek di DevTools: Application → Manifest
```

### 5. Lighthouse Audit
```bash
# Buka DevTools (F12)
# Tab: Lighthouse
# Select: Progressive Web App
# Click: Generate report
# Target score: 90+
```

## PWA Checklist

### Core Requirements
- [x] HTTPS (required for PWA)
- [x] Manifest file dengan required fields
- [x] Service worker registered
- [x] Icons (192x192 dan 512x512 minimum)
- [x] Offline page
- [x] Responsive design
- [x] Fast load time

### Enhanced Features
- [x] App shortcuts
- [x] Install prompt
- [x] Splash screen
- [x] Theme color
- [x] Background sync (ready)
- [x] Push notifications (ready)
- [ ] Share target API (optional)
- [ ] File handling (optional)

## Konfigurasi Manifest

### App Identity
```json
{
  "name": "Sistem Absensi Siswa",
  "short_name": "Absensi",
  "description": "Aplikasi absensi siswa dengan face recognition dan GPS tracking"
}
```

### Display
```json
{
  "display": "standalone",
  "orientation": "portrait",
  "theme_color": "#4f46e5",
  "background_color": "#ffffff"
}
```

### Icons
```json
{
  "icons": [
    {
      "src": "/pwa-icons/icon-192x192.png",
      "sizes": "192x192",
      "type": "image/png",
      "purpose": "any maskable"
    },
    {
      "src": "/pwa-icons/icon-512x512.png",
      "sizes": "512x512",
      "type": "image/png",
      "purpose": "any maskable"
    }
  ]
}
```

### Shortcuts
```json
{
  "shortcuts": [
    {
      "name": "Face Scan",
      "url": "/face-attendance",
      "icons": [...]
    },
    {
      "name": "Riwayat Absensi",
      "url": "/attendance-history",
      "icons": [...]
    }
  ]
}
```

## Service Worker Strategies

### Network First (HTML Pages)
```javascript
// Try network first, fallback to cache
fetch(request)
  .then(response => {
    cache.put(request, response.clone());
    return response;
  })
  .catch(() => cache.match(request))
```

### Cache First (Static Assets)
```javascript
// Try cache first, fallback to network
cache.match(request)
  .then(cached => cached || fetch(request))
```

### Stale While Revalidate
```javascript
// Return cached, update in background
cache.match(request)
  .then(cached => {
    const fetchPromise = fetch(request).then(response => {
      cache.put(request, response.clone());
      return response;
    });
    return cached || fetchPromise;
  })
```

## Background Sync

### Save Attendance Offline
```javascript
// In your attendance submission code
if ('serviceWorker' in navigator && 'SyncManager' in window) {
  // Save to IndexedDB
  await saveToIndexedDB(attendanceData);
  
  // Register sync
  const registration = await navigator.serviceWorker.ready;
  await registration.sync.register('sync-attendance');
}
```

### Service Worker Sync Handler
```javascript
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-attendance') {
    event.waitUntil(syncAttendance());
  }
});
```

## Push Notifications (Future)

### Request Permission
```javascript
if ('Notification' in window) {
  const permission = await Notification.requestPermission();
  if (permission === 'granted') {
    // Subscribe to push
    const subscription = await registration.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: 'YOUR_VAPID_PUBLIC_KEY'
    });
    
    // Send subscription to server
    await fetch('/api/push-subscribe', {
      method: 'POST',
      body: JSON.stringify(subscription)
    });
  }
}
```

### Send from Server (Laravel)
```php
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

$subscription = Subscription::create([
    'endpoint' => $user->push_endpoint,
    'keys' => [
        'p256dh' => $user->push_p256dh,
        'auth' => $user->push_auth
    ]
]);

$webPush = new WebPush([
    'VAPID' => [
        'subject' => 'mailto:admin@example.com',
        'publicKey' => env('VAPID_PUBLIC_KEY'),
        'privateKey' => env('VAPID_PRIVATE_KEY')
    ]
]);

$webPush->sendOneNotification(
    $subscription,
    json_encode([
        'title' => 'Reminder Absensi',
        'body' => 'Jangan lupa absen hari ini!',
        'url' => '/face-attendance'
    ])
);
```

## Troubleshooting

### PWA Not Installable
**Penyebab**:
- Tidak menggunakan HTTPS
- Manifest tidak valid
- Service worker tidak registered
- Icons tidak ada

**Solusi**:
```bash
# Check manifest
curl http://localhost/manifest.json

# Check service worker
# DevTools → Application → Service Workers

# Check icons
ls public/pwa-icons/

# Use HTTPS (ngrok)
ngrok http 80
```

### Service Worker Not Updating
**Penyebab**:
- Browser cache
- Service worker cache

**Solusi**:
```javascript
// Force update
navigator.serviceWorker.getRegistrations().then(registrations => {
  registrations.forEach(registration => {
    registration.update();
  });
});

// Or in DevTools:
// Application → Service Workers → Update
```

### Offline Page Not Showing
**Penyebab**:
- offline.html tidak di-cache
- Service worker fetch handler error

**Solusi**:
```javascript
// Ensure offline.html is in STATIC_CACHE
const STATIC_CACHE = [
    '/',
    '/offline.html',  // ← Must be here
    '/manifest.json'
];
```

### Install Banner Not Showing
**Penyebab**:
- PWA sudah installed
- Criteria tidak terpenuhi
- User sudah dismiss 3x

**Solusi**:
```bash
# Uninstall PWA
# Clear browser data
# Reload page

# Or test in Incognito mode
```

## Best Practices

### 1. Cache Strategy
- HTML: Network first (always fresh)
- CSS/JS: Cache first (fast load)
- Images: Cache first with expiry
- API: Network only (no cache)

### 2. Update Strategy
- Version cache name (v1.0.0)
- Delete old caches on activate
- Show update notification
- Reload on user confirmation

### 3. Offline Strategy
- Cache critical pages
- Show offline indicator
- Queue actions for sync
- Inform user about offline mode

### 4. Performance
- Lazy load service worker
- Minimize cache size
- Use compression
- Optimize icons

## Metrics & Analytics

### Track PWA Usage
```javascript
// Track install
window.addEventListener('appinstalled', () => {
  gtag('event', 'pwa_install');
});

// Track display mode
if (window.matchMedia('(display-mode: standalone)').matches) {
  gtag('event', 'pwa_launch');
}

// Track offline usage
window.addEventListener('offline', () => {
  gtag('event', 'offline_mode');
});
```

## Resources
- [PWA Builder](https://www.pwabuilder.com/)
- [Workbox (Google)](https://developers.google.com/web/tools/workbox)
- [MDN PWA Guide](https://developer.mozilla.org/en-US/docs/Web/Progressive_web_apps)
- [Web.dev PWA](https://web.dev/progressive-web-apps/)
- [Can I Use PWA](https://caniuse.com/?search=pwa)

---

**Dibuat**: 11 Februari 2026
**Status**: ✅ Implemented & Ready to Test
**Next Steps**: Generate PWA icons dan test install di mobile device
