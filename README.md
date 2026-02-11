# Aplikasi Web Absensi Siswa dengan Face Recognition

![Aplikasi Web Absensi Siswa QR Code GPS](./screenshots/hero.png)

Aplikasi web absensi Siswa menggunakan Face Recognition, QR Code, dan GPS dengan sistem Role-Based Access Control (RBAC) untuk Guru dan Siswa.

## ✨ Fitur Utama

### 🎭 Face Recognition
- Pendaftaran wajah (Face Enrollment) menggunakan face-api.js
- Verifikasi wajah real-time di browser
- Absensi masuk/keluar dengan face recognition
- Skor kemiripan wajah untuk validasi
- **Anti-Spoofing Detection** - Mencegah penggunaan foto palsu (foto dari layar/cetakan)
- **Liveness Detection** - Deteksi kedipan mata dan gerakan kepala real-time

### 👥 Role-Based Access Control (RBAC)
- **Guru (Teacher)**: Kelola siswa, monitor kehadiran, export laporan
- **Siswa (Student)**: Daftar wajah, absensi, lihat riwayat pribadi
- **Admin**: Akses penuh ke sistem
- **Superadmin**: Kontrol penuh termasuk kelola admin

### 📍 Location-Based Attendance
- Validasi lokasi menggunakan GPS
- Multiple office locations support
- Radius-based attendance validation

### 📊 Reporting & Analytics
- Riwayat absensi dengan detail face recognition
- Export data ke Excel/PDF
- Statistik kehadiran real-time

## Teknologi yang Digunakan

* [Laravel 11](https://laravel.com/)
* [Laravel Jetstream](https://jetstream.laravel.com/)
* [face-api.js](https://github.com/justadudewhohacks/face-api.js) - Face Recognition
* [Endroid QR Code](https://github.com/endroid/qr-code)
* [Leaflet.js](https://leafletjs.com/)
* [OpenStreetMap](https://www.openstreetmap.org/)
* MySQL/MariaDB

## 🚀 Quick Start

### Login Credentials

**Guru (Teacher):**
```
Email: guru1@example.com
Password: guru123
```

**Siswa (Student):**
```
Email: ahmad.fauzi@student.com
Password: student123
```

**Admin:**
```
Email: admin@example.com
Password: admin
```

📖 **Lihat [QUICK_START_RBAC.md](QUICK_START_RBAC.md) untuk panduan lengkap**

## Instalasi

### Prasyarat

* [Composer](https://getcomposer.org)
* [NPM & Node.js](https://nodejs.org) atau [Bun](https://bun.com/)
* PHP 8.3 atau lebih tinggi
* MySQL/MariaDB

---

1. Clone/download repository ini
2. Jalankan perintah `composer run-script post-root-package-install` untuk membuat file `.env`
3. Jalankan perintah `composer install` untuk menginstalasi dependency
4. Jalankan perintah `npm install` untuk menginstalasi dependency Javascript
5. Jalankan perintah `php artisan key:generate --ansi --force` untuk membuat key aplikasi
6. Jalankan perintah `php artisan migrate` untuk membuat tabel database
7. Jalankan perintah `npm run build` untuk membuat file css dan javascript yang diperlukan
8. Jalankan perintah `php artisan serve` untuk menjalankan aplikasi

### Seeder

Pilih salah satu opsi berikut:

* Jalankan perintah `php artisan db:seed DatabaseSeeder` untuk menyiapkan data awal (termasuk Guru & Siswa)
* Jalankan perintah `php artisan db:seed FakeDataSeeder` untuk menyiapkan data awal beserta data dummy (absensi & karyawan)

## 📚 Dokumentasi

- **[RBAC_DOCUMENTATION.md](RBAC_DOCUMENTATION.md)** - Dokumentasi lengkap Role-Based Access Control
- **[QUICK_START_RBAC.md](QUICK_START_RBAC.md)** - Panduan cepat untuk testing RBAC
- **[ANTI_SPOOFING.md](ANTI_SPOOFING.md)** - Dokumentasi Anti-Spoofing Detection
- **[LIVENESS_DETECTION.md](LIVENESS_DETECTION.md)** - Panduan implementasi Liveness Detection (Blink & Head Movement)
- **[DEPLOYMENT.md](DEPLOYMENT.md)** - Panduan deployment

## Fitur & Pratinjau

### 🎓 Siswa (Student)

#### Face Registration
- Daftar wajah dengan deteksi real-time
- Validasi kualitas foto wajah
- Update foto wajah kapan saja

#### Face Attendance
- Absen masuk/keluar dengan face recognition
- Validasi lokasi GPS
- Feedback langsung dengan skor kemiripan

#### Attendance History
- Lihat riwayat kehadiran pribadi
- Detail face recognition per absensi
- Statistik kehadiran bulanan

### 👨‍🏫 Guru (Teacher)

#### Student Management
- Kelola data siswa (CRUD)
- Monitor face registration siswa
- Atur shift dan jadwal

#### Attendance Monitoring
- Monitor kehadiran real-time
- Filter berdasarkan tanggal, status, siswa
- Lihat detail face recognition

#### Reports & Export
- Rekap kehadiran per periode
- Export ke Excel/PDF
- Statistik kehadiran kelas

### User/Siswa

| Scan Page                                | Scan Page (Mobile)                                     |
| ---------------------------------------- | ------------------------------------------------------ |
| ![Scan](./screenshots/presensi-scan.png) | ![Scan mobile](./screenshots/presensi-scan-mobile.png) |

| Pengajuan Absensi                                       | Riwayat Absensi siswa                             |
| ------------------------------------------------------- | ---------------------------------------------------- |
| ![Pengajuan Absensi](./screenshots/pengajuan-izin.jpeg) | ![Riwayat Absensi](./screenshots/presensi-user.jpeg) |

### Admin & Superadmin

| Dashboard Admin                                  | Dashboard Admin Dark                                 |
| ------------------------------------------------ | ---------------------------------------------------- |
| ![Dashboard](./screenshots/dashboard-light.jpeg) | ![Dashboard Dark](./screenshots/dashboard-dark.jpeg) |

| Barcode                                | Create/Edit Barcode                                            |
| -------------------------------------- | -------------------------------------------------------------- |
| ![Barcode](./screenshots/barcode.jpeg) | ![Create Edit Barcode](./screenshots/create-edit-barcode.jpeg) |

| Absensi siswa                                    |                                                         |                                                       |
| --------------------------------------------------- | ------------------------------------------------------- | ----------------------------------------------------- |
| Absensi per hari                                    | Absensi per minggu                                      | Absensi per bulan                                     |
| ![Absensi per hari](./screenshots/absensi-hari.png) | ![Absensi per minggu](./screenshots/absensi-minggu.png) | ![Absensi per bulan](./screenshots/absensi-bulan.png) |

| Data Siswa                                 | Create/Edit Data Siswa                                            |
| --------------------------------------------- | -------------------------------------------------------------------- |
| ![Data Siswa](./screenshots/siswa.jpeg) | ![Create Edit Data Siswa](./screenshots/create-edit-siswa.png) |

| Export/Import from/to XLSX                                       |                                                                                   |
| ---------------------------------------------------------------- | --------------------------------------------------------------------------------- |
| Export/Import Data Siswa & User                               | Export/Import Data Siswa & User + Preview Data                                 |
| ![Export/Import Data Siswa](./screenshots/export-user.jpeg)   | ![Export/Import Data Siswa + Preview](./screenshots/export-user-preview.jpeg)  |
| Export/Import Data Absensi & User                                | Export/Import Data Absensi & User + Preview Data                                  |
| ![Export/Import Data Absensi](./screenshots/export-absensi.jpeg) | ![Export/Import Data Absensi + Preview](./screenshots/export-absensi-preview.png) |

## Donasi ❤

[![Donate saweria](https://img.shields.io/badge/Donate-Saweria-red?style=for-the-badge&link=https%3A%2F%2Fsaweria.co%2Fxiboxann)](https://saweria.co/Brodimss)

Atau, beri star...⭐⭐⭐⭐
