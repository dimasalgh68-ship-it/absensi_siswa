# Integrasi API Kalender Akademik

## Overview
Sistem kalender akademik telah diintegrasikan dengan API hari libur nasional Indonesia untuk otomatis mengambil dan menyimpan data hari libur.

## API yang Digunakan
- **Endpoint**: `https://api-harilibur.vercel.app/api`
- **Method**: GET
- **Parameter**: `year` (tahun)
- **Response**: Object dengan array holidays:
  ```json
  {
    "holidays": [
      {
        "holiday_date": "2026-01-01",
        "holiday_name": "Tahun Baru Masehi",
        "is_national_holiday": true
      }
    ]
  }
  ```

## API Alternatif (Fallback)
Jika API utama tidak tersedia, sistem akan mencoba API alternatif:
- `https://dayoffapi.vercel.app/api`
- Format response yang sama

## Fitur

### 1. Sinkronisasi Manual via UI
- Tombol "Sync Libur Nasional" di halaman kalender
- Loading state saat proses sync
- Notifikasi sukses/error setelah sync
- Menampilkan jumlah data yang ditambahkan/diperbarui

### 2. Sinkronisasi via Command Line
```bash
php artisan holidays:fetch {year?}
```
- Parameter `year` opsional (default: tahun sekarang)
- Menampilkan progress dan summary di console

### 3. Auto Update
- Jika data sudah ada, akan diperbarui (bukan skip)
- Mencegah duplikasi data
- Menyimpan informasi apakah cuti bersama atau libur nasional

### 4. Fallback System
- Jika API tidak tersedia, sistem akan menggunakan data manual
- Data manual tersimpan di controller untuk tahun 2026
- Dapat diupdate setiap tahun dengan menambahkan data baru
- Sistem mencoba beberapa API sebelum fallback ke data manual

## Cara Penggunaan

### Via Web Interface (Admin)
1. Login sebagai admin
2. Buka menu "Kalender Akademik" di sidebar
3. Klik tombol "Sync Libur Nasional"
4. Tunggu proses selesai
5. Lihat notifikasi hasil sync
6. Klik "Kelola Event" untuk menambah/edit event manual

### Via Web Interface (User/Siswa)
1. Login sebagai user/siswa
2. Buka menu "Kalender Akademik" di navigasi
3. Lihat kalender dan event mendatang
4. User tidak bisa sync (hanya admin yang bisa)

### Via Command Line
```bash
# Sync untuk tahun sekarang
php artisan holidays:fetch

# Sync untuk tahun tertentu
php artisan holidays:fetch 2026
```

### Otomatis via Scheduler (Opsional)
Tambahkan di `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    // Sync holidays setiap awal tahun
    $schedule->command('holidays:fetch')
        ->yearlyOn(1, 1, '00:00');
}
```

## Data yang Disimpan

Setiap hari libur disimpan sebagai `AcademicEvent` dengan:
- **title**: Nama hari libur (dari API)
- **description**: "Hari libur nasional - Cuti Bersama/Libur Nasional"
- **start_date**: Tanggal libur
- **end_date**: Tanggal libur (sama dengan start_date)
- **type**: "holiday"
- **color**: "#ef4444" (merah)
- **is_active**: true

## Error Handling

### Timeout
- API timeout diset 10 detik
- Jika timeout, akan menampilkan error message

### API Down
- Menampilkan pesan error yang user-friendly
- Tidak akan crash aplikasi

### No Data
- Menampilkan info jika tidak ada data untuk tahun tersebut

## UI Features

### Loading State
- Button disabled saat sync
- Icon berputar (spinning)
- Text berubah menjadi "Sedang Sync..."

### Notifications
- **Success**: Hijau - menampilkan jumlah data ditambahkan/diperbarui
- **Error**: Merah - menampilkan pesan error
- **Info**: Biru - menampilkan informasi tambahan

### Info Box
- Menjelaskan cara kerja sinkronisasi
- Ditampilkan di atas kalender

## Testing

### Test Manual Sync
1. Buka kalender akademik
2. Klik "Sync Libur Nasional"
3. Verifikasi data muncul di kalender
4. Cek database table `academic_events`

### Test Command
```bash
php artisan holidays:fetch 2026
```

### Test API Response
```bash
curl "https://api-harilibur.vercel.app/api?year=2026"
```

Atau test dengan browser:
```
https://api-harilibur.vercel.app/api?year=2026
```

## Troubleshooting

### API Tidak Merespon
- Cek koneksi internet
- Cek apakah API masih aktif
- Coba dengan tahun yang berbeda

### Data Tidak Muncul
- Cek table `academic_events` di database
- Pastikan `is_active` = true
- Clear cache jika perlu

### Duplikasi Data
- Sistem sudah mencegah duplikasi berdasarkan tanggal dan type
- Jika ada duplikasi, hapus manual dari database

## Future Improvements

1. **Auto-sync on first load**: Otomatis sync jika belum ada data untuk tahun tersebut
2. **Sync multiple years**: Sync beberapa tahun sekaligus
3. **Cache API response**: Cache response API untuk mengurangi request
4. **Fallback API**: Gunakan API alternatif jika primary API down
5. **Notification system**: Email/push notification saat ada libur baru

## Related Files

- `app/Http/Controllers/AcademicCalendarController.php` - Controller untuk kalender
- `app/Console/Commands/FetchHolidaysCommand.php` - Command untuk sync via CLI
- `app/Models/AcademicEvent.php` - Model untuk event akademik
- `resources/views/academic-calendar/index.blade.php` - View untuk user/siswa
- `resources/views/admin/academic-calendar.blade.php` - View untuk admin
- `resources/views/layouts/partials/admin-sidebar.blade.php` - Sidebar admin
- `routes/web.php` - Route definitions

## Routes

### User Routes
- `GET /academic-calendar` - Tampilan kalender untuk user
- `POST /academic-calendar/sync-holidays` - Sync holidays (user)

### Admin Routes
- `GET /admin/academic-calendar` - Tampilan kalender untuk admin
- `POST /admin/academic-calendar/sync-holidays` - Sync holidays (admin)
- `GET /admin/academic-events` - Kelola event akademik

## API Documentation

API yang digunakan: https://api-harilibur.vercel.app/

Alternatif API hari libur Indonesia:
- https://github.com/guangrei/APIHariLibur_V2
- https://github.com/jonathanfilbert/api-hari-libur

## Notes

- API menggunakan data resmi dari pemerintah Indonesia
- Data diperbarui setiap tahun
- Mencakup hari libur nasional dan cuti bersama
- Gratis dan tidak memerlukan API key
