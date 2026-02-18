# Fitur Laporan Siswa Tidak Hadir

## Deskripsi

Fitur ini memungkinkan admin dan guru untuk mencetak laporan siswa yang TIDAK MEMILIKI catatan kehadiran sama sekali dalam periode tertentu (hari, minggu, atau bulan).

## Fitur Utama

### 1. Filter Periode
- Per Hari: Siswa yang tidak hadir pada tanggal tertentu
- Per Minggu: Siswa yang tidak hadir selama 1 minggu
- Per Bulan: Siswa yang tidak hadir selama 1 bulan

### 2. Filter Tambahan
- Divisi: Filter berdasarkan divisi/kelas
- Jabatan: Filter berdasarkan jabatan
- Pendidikan: Filter berdasarkan tingkat pendidikan

### 3. Informasi Laporan
- Daftar siswa yang tidak hadir
- Data lengkap siswa (NISN, Nama, Divisi, Jabatan, Pendidikan, Kontak)
- Ringkasan statistik (Total siswa, Jumlah tidak hadir, Persentase)
- Periode laporan yang jelas
- Tanggal cetak

### 4. Format PDF
- Layout profesional
- Mudah dicetak
- Tanda tangan Kepala Sekolah dan Admin
- Catatan penting

## Cara Menggunakan

### Dari Halaman Attendance

1. Login sebagai Admin atau Guru
2. Buka menu "Data Absensi"
3. Pilih filter periode:
   - Per Hari: Pilih tanggal
   - Per Minggu: Pilih minggu
   - Per Bulan: Pilih bulan
4. (Opsional) Pilih filter tambahan (Divisi, Jabatan, Pendidikan)
5. Klik tombol "Laporan Tidak Hadir" (tombol merah)
6. PDF akan otomatis ter-download/terbuka

### Akses Langsung via URL

```
GET /admin/attendances/absent-report?date=2026-02-11
GET /admin/attendances/absent-report?week=2026-W07
GET /admin/attendances/absent-report?month=2026-02
GET /admin/attendances/absent-report?month=2026-02&division=1
```

## Implementasi Teknis

### 1. Controller Method

**File:** `app/Http/Controllers/Admin/AttendanceController.php`

```php
public function absentReport(Request $request)
{
    // Validasi input
    $request->validate([
        'date' => 'nullable|date_format:Y-m-d',
        'month' => 'nullable|date_format:Y-m',
        'week' => 'nullable',
        'division' => 'nullable|exists:divisions,id',
        'job_title' => 'nullable|exists:job_titles,id',
    ]);

    // Hitung periode
    // Get all students
    // Get students with attendance
    // Filter absent students
    // Generate PDF
}
```

### 2. Route

**File:** `routes/web.php`

```php
Route::get('/attendances/absent-report', [AttendanceController::class, 'absentReport'])
    ->name('admin.attendances.absent-report');
```

### 3. View Template

**File:** `resources/views/admin/attendances/absent-report.blade.php`

Template PDF dengan:
- Header (Nama sekolah, Judul laporan, Periode)
- Info Section (Detail periode, filter, tanggal cetak)
- Summary Box (Statistik ringkasan)
- Table (Daftar siswa tidak hadir)
- Footer (Catatan penting)
- Signature Section (Tanda tangan)

### 4. UI Button

**File:** `resources/views/livewire/admin/attendance.blade.php`

```blade
<x-danger-button
  href="{{ route('admin.attendances.absent-report', [...]) }}"
  class="flex justify-center gap-2 bg-red-600 hover:bg-red-700">
  Laporan Tidak Hadir
  <x-heroicon-o-exclamation-triangle class="h-5 w-5" />
</x-danger-button>
```

## Logika Bisnis

### Definisi "Tidak Hadir"

Siswa dianggap "tidak hadir" jika:
- TIDAK memiliki catatan attendance sama sekali dalam periode yang dipilih
- Tidak ada record di tabel `attendances` untuk user tersebut dalam date range

### Perbedaan dengan Status "Alpha"

- **Alpha**: Siswa tercatat di sistem tapi tidak hadir (status = 'absent')
- **Tidak Hadir**: Siswa TIDAK TERCATAT sama sekali di sistem

### Query Logic

```php
// Get all students
$allStudents = User::whereIn('group', ['user', 'student'])
    ->with(['division', 'jobTitle', 'education'])
    ->get();

// Get students who have attendance
$studentsWithAttendance = Attendance::whereBetween('date', [$start, $end])
    ->pluck('user_id')
    ->unique();

// Filter absent students
$absentStudents = $allStudents->filter(function ($student) use ($studentsWithAttendance) {
    return !$studentsWithAttendance->contains($student->id);
});
```

## Contoh Output PDF

### Header
```
[Logo Sekolah]
NAMA SEKOLAH
LAPORAN SISWA TIDAK HADIR
Bulan: Februari 2026
```

### Info Section
```
Periode Laporan: 01 Februari 2026 s/d 28 Februari 2026 (28 hari)
Divisi: Kelas 10A
Tanggal Cetak: 11 Februari 2026, 14:30 WIB
```

### Summary Box
```
Ringkasan
─────────────────────────────
Total Siswa: 30
Tidak Hadir: 3
Persentase: 10.0%
```

### Table
```
No | NISN      | Nama Siswa    | Divisi   | Jabatan | Pendidikan | Kontak
1  | 123456789 | Ahmad Fauzi   | Kelas 10A| Siswa   | SMA        | 08123...
2  | 987654321 | Siti Nurhaliza| Kelas 10A| Siswa   | SMA        | 08234...
3  | 456789123 | Budi Santoso  | Kelas 10A| Siswa   | SMA        | 08345...
```

### Footer
```
Catatan:
- Laporan ini menampilkan siswa yang TIDAK MEMILIKI catatan kehadiran
- Siswa yang tercantum tidak melakukan absensi masuk maupun keluar
- Untuk informasi lebih detail, silakan hubungi siswa yang bersangkutan
```

## Use Cases

### 1. Monitoring Siswa Tidak Aktif
Admin dapat mengidentifikasi siswa yang sama sekali tidak melakukan absensi dalam periode tertentu, yang mungkin mengindikasikan:
- Siswa tidak aktif
- Siswa pindah sekolah
- Masalah teknis (belum registrasi wajah, dll)

### 2. Follow-up Kehadiran
Guru dapat melakukan follow-up kepada siswa yang tidak hadir dengan:
- Menghubungi siswa/orang tua
- Mengecek status siswa
- Memberikan bantuan jika ada masalah

### 3. Laporan Bulanan
Sekolah dapat membuat laporan bulanan untuk:
- Evaluasi kehadiran
- Laporan ke dinas pendidikan
- Dokumentasi administrasi

### 4. Identifikasi Masalah Sistem
Jika banyak siswa tidak hadir, mungkin ada masalah:
- Sistem face recognition bermasalah
- Siswa belum registrasi wajah
- Masalah GPS/lokasi

## Permission & Access Control

### Siapa yang Bisa Akses?

- **Superadmin**: Full access
- **Admin**: Full access
- **Teacher**: Access (sesuai kelas yang diampu)
- **Student**: No access

### Middleware

```php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/attendances/absent-report', ...);
});
```

## Testing

### Test Case 1: Laporan Per Hari
```
Input:
- date: 2026-02-11
- division: null
- jobTitle: null

Expected:
- PDF dengan siswa yang tidak hadir pada 11 Feb 2026
- Total days: 1
```

### Test Case 2: Laporan Per Bulan
```
Input:
- month: 2026-02
- division: 1
- jobTitle: null

Expected:
- PDF dengan siswa divisi 1 yang tidak hadir di Feb 2026
- Total days: 28 (atau 29 jika kabisat)
```

### Test Case 3: Tidak Ada Siswa Tidak Hadir
```
Input:
- date: 2026-02-11

Expected:
- PDF dengan pesan "Tidak Ada Data"
- Pesan positif: "Semua siswa memiliki catatan kehadiran"
```

### Test Case 4: Filter Kombinasi
```
Input:
- month: 2026-02
- division: 1
- jobTitle: 2

Expected:
- PDF dengan siswa divisi 1 dan jabatan 2 yang tidak hadir
```

## Troubleshooting

### PDF Tidak Ter-generate

**Penyebab:**
- DomPDF tidak terinstall
- Memory limit PHP terlalu kecil

**Solusi:**
```bash
composer require barryvdh/laravel-dompdf
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### Data Tidak Sesuai

**Penyebab:**
- Filter tidak diterapkan dengan benar
- Query logic salah

**Solusi:**
- Cek parameter request
- Debug query dengan `dd($absentStudents)`

### PDF Kosong

**Penyebab:**
- Semua siswa hadir (ini bagus!)
- View template error

**Solusi:**
- Cek apakah memang semua siswa hadir
- Cek view template untuk error

## Future Enhancements

### 1. Export ke Excel
Tambahkan opsi export ke Excel selain PDF

### 2. Email Notification
Kirim email otomatis ke siswa yang tidak hadir

### 3. SMS Notification
Kirim SMS ke orang tua siswa yang tidak hadir

### 4. Grafik Trend
Tambahkan grafik trend siswa tidak hadir per bulan

### 5. Comparison Report
Bandingkan periode saat ini dengan periode sebelumnya

### 6. Automated Scheduling
Jadwalkan laporan otomatis setiap minggu/bulan

## Kesimpulan

Fitur Laporan Siswa Tidak Hadir membantu sekolah untuk:
- Monitoring kehadiran siswa lebih efektif
- Identifikasi siswa yang perlu perhatian khusus
- Dokumentasi administrasi yang lengkap
- Follow-up yang lebih cepat

Fitur ini melengkapi sistem absensi dengan memberikan insight tentang siswa yang sama sekali tidak melakukan absensi, bukan hanya yang alpha.

---

**Status:** Ready to Use

**Version:** 1.0.0

**Last Updated:** February 2026
