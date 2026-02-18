# Perbaikan Form Registrasi - Preserve Input on Error

## Masalah
Ketika user mengisi form registrasi dan ada error validasi (misalnya email sudah terdaftar, password tidak cocok, dll), semua data yang sudah diinput hilang dan user harus mengisi ulang dari awal. Ini sangat menyebalkan dan membuang waktu.

## Solusi yang Diterapkan

### 1. Perbaikan Form Registrasi User
**File**: `resources/views/auth/register.blade.php`

**Perubahan**:
Mengubah semua input field dari Alpine.js binding (`:value="old('...')"`) menjadi Laravel Blade syntax (`value="{{ old('...') }}"`) yang benar.

**Field yang diperbaiki**:
- ✅ Name (Nama Lengkap)
- ✅ NISN (Nomor Induk Siswa)
- ✅ Email
- ✅ Phone (No. Telepon)
- ✅ Gender (Jenis Kelamin) - sudah benar sebelumnya
- ✅ Education (Kelas) - sudah benar sebelumnya
- ✅ City (Kota)
- ✅ Address (Alamat Lengkap) - sudah benar sebelumnya

**Sebelum**:
```blade
<input id="name" type="text" name="name" :value="old('name')" required>
<input id="email" type="email" name="email" :value="old('email')" required>
<input id="phone" type="text" name="phone" :value="old('phone')" required>
```

**Sesudah**:
```blade
<input id="name" type="text" name="name" value="{{ old('name') }}" required>
<input id="email" type="email" name="email" value="{{ old('email') }}" required>
<input id="phone" type="text" name="phone" value="{{ old('phone') }}" required>
```

### 2. Form Admin (Livewire)
**File**: `app/Livewire/Forms/UserForm.php`

**Status**: ✅ Sudah benar!

Livewire Form sudah otomatis menyimpan nilai input ketika ada error validasi. Tidak perlu perubahan karena Livewire secara default akan preserve state component.

## Cara Kerja Laravel `old()` Helper

Laravel menyediakan helper function `old()` yang akan:
1. Menyimpan semua input user ke session ketika ada validation error
2. Mengembalikan nilai input sebelumnya saat form di-render ulang
3. Menghapus data dari session setelah form berhasil disubmit

**Syntax**:
```blade
<!-- Input text -->
<input name="name" value="{{ old('name') }}">

<!-- Select dropdown -->
<select name="gender">
    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
</select>

<!-- Textarea -->
<textarea name="address">{{ old('address') }}</textarea>

<!-- Checkbox -->
<input type="checkbox" name="terms" {{ old('terms') ? 'checked' : '' }}>
```

## Testing

### Test Form Registrasi User

1. **Buka halaman registrasi**:
   ```
   http://localhost/register
   ```

2. **Isi form dengan data yang salah**:
   - Nama: John Doe
   - NISN: 12345
   - Email: test@test.com (email yang sudah terdaftar)
   - Phone: 08123456789
   - Gender: Laki-laki
   - Kelas: Pilih salah satu
   - Kota: Jakarta
   - Alamat: Jl. Test No. 123
   - Password: 123
   - Konfirmasi Password: 456 (tidak cocok)

3. **Submit form**

4. **Hasil yang diharapkan**:
   - ❌ Error muncul: "Email sudah terdaftar" atau "Password tidak cocok"
   - ✅ Semua field tetap terisi dengan data yang sudah diinput
   - ✅ User hanya perlu memperbaiki field yang error (email atau password)
   - ✅ Tidak perlu mengisi ulang semua field dari awal

### Test Form Admin (Create/Edit User)

1. **Login sebagai admin**

2. **Buka halaman tambah user**:
   ```
   http://localhost/admin/employees
   ```

3. **Klik tombol "Tambah Karyawan/Siswa"**

4. **Isi form dengan data yang salah**:
   - Nama: Test User
   - Email: admin@test.com (email yang sudah ada)
   - Submit form

5. **Hasil yang diharapkan**:
   - ❌ Error muncul: "Email sudah terdaftar"
   - ✅ Semua field tetap terisi (Livewire otomatis preserve state)
   - ✅ User hanya perlu mengubah email

## Keuntungan

### User Experience
✅ User tidak frustasi karena harus mengisi ulang form
✅ Menghemat waktu user
✅ Mengurangi kemungkinan user meninggalkan proses registrasi
✅ Lebih user-friendly dan professional

### Developer
✅ Menggunakan Laravel best practice
✅ Code lebih clean dan maintainable
✅ Tidak perlu JavaScript tambahan untuk preserve input
✅ Built-in Laravel feature yang reliable

## Penjelasan Teknis

### Mengapa `:value="old('name')"` Tidak Bekerja?

Alpine.js binding (`:value`) adalah reactive binding yang mengharapkan JavaScript expression. Ketika kita menulis `:value="old('name')"`, Alpine.js mencoba mencari variable JavaScript bernama `old`, bukan memanggil Laravel helper function.

**Solusi**: Gunakan Blade syntax `value="{{ old('name') }}"` yang akan di-render di server-side sebelum dikirim ke browser.

### Flow Validation Error

```
1. User submit form
   ↓
2. Laravel validate input
   ↓
3. Validation failed
   ↓
4. Laravel flash input ke session
   ↓
5. Redirect back ke form
   ↓
6. Blade render form dengan old() values
   ↓
7. User lihat form dengan data yang sudah diisi
   ↓
8. User perbaiki field yang error
   ↓
9. Submit ulang
```

## Best Practices

### 1. Selalu Gunakan `old()` Helper
```blade
<!-- ✅ BENAR -->
<input name="name" value="{{ old('name') }}">

<!-- ❌ SALAH -->
<input name="name" :value="old('name')">
<input name="name"> <!-- tidak preserve value -->
```

### 2. Untuk Select Dropdown
```blade
<select name="gender">
    <option value="">Pilih Gender</option>
    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
        Laki-laki
    </option>
    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
        Perempuan
    </option>
</select>
```

### 3. Untuk Textarea
```blade
<textarea name="address">{{ old('address') }}</textarea>
```

### 4. Untuk Checkbox/Radio
```blade
<input type="checkbox" name="terms" {{ old('terms') ? 'checked' : '' }}>
```

### 5. Dengan Default Value
```blade
<!-- Jika ada default value -->
<input name="city" value="{{ old('city', 'Jakarta') }}">
<!-- Akan gunakan old value jika ada, jika tidak gunakan 'Jakarta' -->
```

## Troubleshooting

### Input Tidak Preserve Setelah Error

**Penyebab**:
- Menggunakan Alpine.js binding `:value` instead of Blade `value`
- Session tidak enabled
- Redirect tidak menggunakan `back()` atau `redirect()->withInput()`

**Solusi**:
```blade
<!-- Ganti dari -->
<input :value="old('name')">

<!-- Menjadi -->
<input value="{{ old('name') }}">
```

### Select Dropdown Tidak Preserve

**Penyebab**:
- Tidak menggunakan `selected` attribute dengan kondisi

**Solusi**:
```blade
<option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
    Laki-laki
</option>
```

## File yang Diubah

### File Diubah
1. `resources/views/auth/register.blade.php`
   - Perbaiki input: name, nisn, email, phone, city
   - Dari `:value="old('...')"` ke `value="{{ old('...') }}"`

### File Tidak Perlu Diubah
1. `app/Livewire/Forms/UserForm.php`
   - Livewire sudah otomatis preserve state
   - Tidak perlu perubahan

### File Dokumentasi
1. `FIX_REGISTRATION_PRESERVE_INPUT.md` - Dokumentasi ini

## Referensi
- [Laravel Validation - Old Input](https://laravel.com/docs/validation#old-input)
- [Laravel Helpers - old()](https://laravel.com/docs/helpers#method-old)
- [Livewire - Form Validation](https://livewire.laravel.com/docs/forms#validation)

---

**Dibuat**: 11 Februari 2026
**Status**: ✅ Selesai dan Tested
