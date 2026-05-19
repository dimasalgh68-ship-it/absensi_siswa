# E-ABSENSI - Sistem Absensi dengan Face Recognition & GPS

![E-ABSENSI](./screenshots/hero.png)

Aplikasi web absensi modern menggunakan Face Recognition, GPS Location Tracking, dan Progressive Web App (PWA) dengan sistem Role-Based Access Control (RBAC).

## ✨ Fitur Utama

### 🎭 Face Recognition
- Pendaftaran wajah (Face Enrollment) menggunakan face-api.js
- Verifikasi wajah real-time di browser
- Absensi masuk/keluar dengan face recognition
- Skor kemiripan wajah untuk validasi
- **Anti-Spoofing Detection** - Mencegah penggunaan foto palsu dengan 10 layer validasi GPS
- **Liveness Detection** - Deteksi kedipan mata dan gerakan kepala real-time

### � Progressive Web App (PWA)
- Install sebagai aplikasi native di smartphone
- Offline support dengan service worker
- Push notifications
- Auto-update content
- Responsive design untuk semua device

### 📍 Advanced Location Tracking
- **10-Layer GPS Anti-Spoofing** - Validasi GPS dengan multiple checks
- Multiple office locations support dengan Google Maps
- Radius-based attendance validation (10m - 5000m)
- GPS metadata logging (accuracy, altitude, speed, heading)
- Mock location detection
- Rate limiting untuk mencegah spam

### 👥 Role-Based Access Control (RBAC)
- **Admin**: Kelola semua data, settings, dan users
- **Teacher**: Monitor kehadiran, kelola siswa, export laporan
- **Student**: Daftar wajah, absensi, lihat riwayat pribadi
- Middleware protection untuk setiap role

### 📊 Reporting & Analytics
- Riwayat absensi dengan detail face recognition
- Export data ke Excel/PDF
- Statistik kehadiran real-time
- Dashboard analytics
- Absent report & calendar view

## 🛠 Teknologi yang Digunakan

### Backend
* [Laravel 11](https://laravel.com/) - PHP Framework
* [Laravel Jetstream](https://jetstream.laravel.com/) - Authentication & Teams
* [Livewire 3](https://livewire.laravel.com/) - Dynamic UI Components
* MySQL/MariaDB - Database

### Frontend
* [Tailwind CSS](https://tailwindcss.com/) - Utility-first CSS
* [Alpine.js](https://alpinejs.dev/) - Lightweight JavaScript
* [face-api.js](https://github.com/justadudewhohacks/face-api.js) - Face Recognition
* [Google Maps API](https://developers.google.com/maps) - Location Services

### PWA & Tools
* Service Worker - Offline support
* Web Manifest - App installation
* [Maatwebsite Excel](https://laravel-excel.com/) - Excel import/export
* [DomPDF](https://github.com/barryvdh/laravel-dompdf) - PDF generation

## 🚀 Quick Start

### Prasyarat

* PHP 8.2 atau lebih tinggi
* [Composer](https://getcomposer.org)
* [Node.js & NPM](https://nodejs.org) atau [Bun](https://bun.sh/)
* MySQL/MariaDB
* Google Maps API Key (untuk location picker)

### Instalasi

1. **Clone repository**
```bash
git clone https://github.com/dimasalgh68-ship-it/absensi_siswa.git
cd absensi_siswa
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi database** di file `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensi_db
DB_USERNAME=root
DB_PASSWORD=
```

5. **Konfigurasi Google Maps API** di file `.env`
```env
GOOGLE_MAPS_API_KEY=your_api_key_here
```

6. **Migrasi database & seeder**
```bash
php artisan migrate
php artisan db:seed DatabaseSeeder
# Atau untuk data dummy:
# php artisan db:seed FakeDataSeeder
```

7. **Build assets**
```bash
npm run build
```

8. **Jalankan aplikasi**
```bash
php artisan serve
```

Akses aplikasi di `http://localhost:8000`

### � Login Credentials

Setelah menjalankan seeder, gunakan kredensial berikut:

**Admin:**
```
Email: admin@example.com
Password: admin123
```

**Teacher:**
```
Email: teacher@example.com
Password: teacher123
```

**Student:**
```
Email: student@example.com
Password: student123
```

## 📱 Install sebagai PWA

1. Buka aplikasi di browser (Chrome/Edge/Safari)
2. Klik tombol "Install App" di navigation bar
3. Atau gunakan menu browser: "Install App" / "Add to Home Screen"
4. Aplikasi akan terinstall seperti aplikasi native

## 🔐 Keamanan & Anti-Spoofing

### 10-Layer GPS Anti-Spoofing
1. ✅ GPS accuracy validation (< 50m)
2. ✅ Altitude reasonability check
3. ✅ Speed validation
4. ✅ Timestamp freshness check
5. ✅ Mock location detection
6. ✅ Coordinate format validation
7. ✅ Heading/bearing validation
8. ✅ Rate limiting (max 1 request/10s)
9. ✅ GPS metadata logging
10. ✅ Database race condition prevention

### Face Recognition Security
- Liveness detection (blink & head movement)
- Minimum similarity threshold (70%)
- Face descriptor storage encryption
- Anti-spoofing untuk foto palsu

## 📸 Screenshots

### Dashboard & Home
| Dashboard Admin | Home Page |
| --- | --- |
| ![Dashboard](./screenshots/dashboard-light.jpeg) | ![Home](./screenshots/hero.png) |

### Face Recognition
| Face Scan | Face Registration |
| --- | --- |
| ![Face Scan](./screenshots/presensi-scan.png) | ![Registration](./screenshots/presensi-scan-mobile.png) |

### Attendance Management
| Attendance History | Daily Report |
| --- | --- |
| ![History](./screenshots/presensi-user.jpeg) | ![Daily](./screenshots/absensi-hari.png) |

### Data Management
| Student Data | Export/Import |
| --- | --- |
| ![Students](./screenshots/karyawan.jpeg) | ![Export](./screenshots/export-user.jpeg) |

## 🚀 Deployment

Lihat [DEPLOYMENT-README.md](DEPLOYMENT-README.md) untuk panduan deployment ke production.

### Deployment Checklist
- [ ] Set `APP_ENV=production` di `.env`
- [ ] Set `APP_DEBUG=false` di `.env`
- [ ] Generate production key: `php artisan key:generate`
- [ ] Optimize: `php artisan optimize`
- [ ] Cache config: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Build assets: `npm run build`
- [ ] Setup SSL certificate (HTTPS required for PWA & GPS)
- [ ] Configure Google Maps API key dengan domain restrictions

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 👨‍💻 Author

**Dimas Al Ghofiqi Binsig**
- GitHub: [@dimasalgh68-ship-it](https://github.com/dimasalgh68-ship-it)

## ❤️ Support

Jika project ini membantu Anda, berikan ⭐ di repository ini!

[![Donate Saweria](https://img.shields.io/badge/Donate-Saweria-red?style=for-the-badge)](https://saweria.co/Brodimss)

---

**Built with ❤️ using Laravel & Modern Web Technologies**
