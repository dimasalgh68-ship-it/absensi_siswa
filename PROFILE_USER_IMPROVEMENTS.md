# Profile User Improvements

## Overview
Halaman profile user telah diperbaiki dengan tampilan yang lebih user-friendly dan fitur yang lebih lengkap.

## Features

### 1. Update Profile Information
Formulir lengkap untuk mengupdate informasi profil dengan 3 section:

#### Data Pribadi
- **Foto Profil**
  - Upload foto baru
  - Preview foto sebelum upload
  - Hapus foto profil
  - Format: JPG, PNG (max 2MB)
  - Rekomendasi: 400x400px

- **Nama Lengkap** - Required
- **NISN** - Nomor Induk Siswa Nasional (Required)
- **Tempat Lahir** - Optional
- **Tanggal Lahir** - Date picker
- **Nomor Telepon** - Required (format: 08xxxxxxxxxx)
- **Jenis Kelamin** - Dropdown (Laki-laki/Perempuan)
- **Alamat Lengkap** - Textarea (Required)
- **Kota/Kabupaten** - Required

#### Informasi Akun
- **Email** - Required, dengan email verification
- **Email Verification Status**
  - Warning jika belum verified
  - Button "Kirim ulang email verifikasi"
  - Success message setelah kirim

#### Informasi Akademik
- **Jurusan** - Dropdown dari master Division
- **Kelas** - Dropdown dari master Education
- **Status** - Dropdown dari master Job Title
- **Jadwal Shift** - Dropdown dengan info waktu
  - Menampilkan nama shift dan jam (contoh: "Pagi (07:00 - 15:00)")
  - Helper text: "Jadwal ini menentukan waktu absensi Anda"

### 2. Update Password
Formulir untuk mengubah password dengan validasi:
- **Password Saat Ini** - Required untuk verifikasi
- **Password Baru** - Minimal 8 karakter
- **Konfirmasi Password** - Harus sama dengan password baru
- Helper text untuk panduan password

### 3. Two Factor Authentication
Fitur keamanan tambahan (jika diaktifkan di config)

### 4. Browser Sessions
Kelola sesi login di berbagai browser/device

### 5. Delete Account
Opsi untuk menghapus akun (jika diaktifkan)

## UI/UX Improvements

### Visual Design
- **Organized Sections**: Data dikelompokkan dengan divider dan heading
- **Icon Integration**: Icon SVG untuk visual cues
- **Color Coding**: 
  - Yellow untuk warning (email not verified)
  - Green untuk success messages
  - Blue untuk info
- **Responsive Layout**: 
  - Mobile: Single column
  - Desktop: Two columns untuk beberapa field

### Form Elements
- **Placeholder Text**: Panduan untuk setiap input
- **Helper Text**: Informasi tambahan di bawah field
- **Error Messages**: Validasi real-time dengan pesan error
- **Loading States**: 
  - Spinner animation saat submit
  - Button disabled saat loading
  - Text berubah "Menyimpan..."

### User Feedback
- **Success Message**: "Tersimpan" dengan icon checkmark
- **Error Handling**: Pesan error spesifik per field
- **Email Verification**: 
  - Warning box jika belum verified
  - Success box setelah kirim ulang
- **Photo Preview**: Preview foto sebelum upload

## Technical Implementation

### Livewire Component
File: `app/Http/Livewire/UpdateProfileInformationForm.php`

#### Key Methods
```php
public function updateProfileInformation()
{
    // Validate all fields
    // Handle photo upload
    // Update user data
    // Dispatch events
}

public function deleteProfilePhoto()
{
    // Delete profile photo
    // Refresh navigation
}

public function mount()
{
    // Initialize state with user data
}
```

#### Validation Rules
- Name: required, string, max:255
- NISN: required, string, max:255
- Email: required, email, max:255
- Phone: required, string, max:255
- Gender: required, in:male,female
- Address: required, string
- City: required, string, max:255
- Birth Date: nullable, date
- Birth Place: nullable, string, max:255
- Division ID: nullable, exists:divisions,id
- Education ID: nullable, exists:educations,id
- Job Title ID: nullable, exists:job_titles,id
- Shift ID: nullable, exists:shifts,id

### View Files
- `resources/views/profile/show.blade.php` - Main profile page
- `resources/views/profile/update-profile-information-form.blade.php` - Profile form
- `resources/views/profile/update-password-form.blade.php` - Password form

### Events
- `saved` - Dispatched after successful save
- `refresh-navigation-menu` - Update navigation with new data

## Field Mapping

### Database Columns
```
users table:
- name (string)
- nisn (string)
- email (string)
- phone (string)
- gender (enum: male, female)
- address (text)
- city (string)
- birth_date (date)
- birth_place (string)
- division_id (foreign key)
- education_id (foreign key)
- job_title_id (foreign key)
- shift_id (foreign key)
- profile_photo_path (string)
```

## Usage Guide

### For Users

#### Update Profile
1. Login ke sistem
2. Klik nama/foto profil di navbar
3. Pilih "Profil Saya"
4. Edit field yang ingin diubah
5. Klik "Simpan Perubahan"
6. Tunggu notifikasi "Tersimpan"

#### Upload Photo
1. Klik "Pilih Foto Baru"
2. Pilih file foto (JPG/PNG, max 2MB)
3. Preview akan muncul
4. Klik "Simpan Perubahan"
5. Foto akan terupdate di navbar

#### Change Password
1. Scroll ke section "Ubah Password"
2. Masukkan password saat ini
3. Masukkan password baru (min 8 karakter)
4. Konfirmasi password baru
5. Klik "Simpan Password"

#### Verify Email
1. Jika muncul warning "Email belum diverifikasi"
2. Klik "Kirim ulang email verifikasi"
3. Cek inbox email
4. Klik link verifikasi di email
5. Email terverifikasi

### For Admins

#### Manage User Profiles
Admins dapat mengedit profile user melalui:
1. Menu "Siswa" di admin panel
2. Klik edit pada user yang ingin diubah
3. Update data yang diperlukan
4. Simpan perubahan

## Validation Messages

### Indonesian Error Messages
- "Nama wajib diisi"
- "NISN wajib diisi"
- "Email tidak valid"
- "Nomor telepon wajib diisi"
- "Jenis kelamin wajib dipilih"
- "Alamat wajib diisi"
- "Kota wajib diisi"
- "Password minimal 8 karakter"
- "Konfirmasi password tidak cocok"

## Security Features

### Password Requirements
- Minimum 8 characters
- Current password verification required
- Password confirmation required

### Email Verification
- Optional but recommended
- Verification link sent to email
- Can resend verification link

### Photo Upload Security
- File type validation (JPG, PNG only)
- File size limit (2MB)
- Stored in secure storage path

## Accessibility

- ✅ Keyboard navigation
- ✅ Screen reader friendly labels
- ✅ Clear error messages
- ✅ Focus indicators
- ✅ Proper form structure

## Browser Support
- Chrome/Edge: ✅ Full support
- Firefox: ✅ Full support
- Safari: ✅ Full support
- Mobile browsers: ✅ Full support

## Performance
- Lazy loading for dropdowns
- Optimized image upload
- Real-time validation
- Efficient state management

## Future Enhancements
1. **Profile Completion Progress**: Show percentage of completed fields
2. **Avatar Generator**: Generate avatar from initials if no photo
3. **Social Media Links**: Add social media profile links
4. **Privacy Settings**: Control what information is visible
5. **Activity Log**: Show recent profile changes
6. **Export Profile**: Download profile data as PDF

## Troubleshooting

### Photo Upload Issues
**Problem**: Photo tidak terupload
**Solution**:
1. Cek ukuran file (max 2MB)
2. Cek format file (JPG/PNG only)
3. Cek permission folder storage
4. Clear browser cache

### Validation Errors
**Problem**: Form tidak bisa disimpan
**Solution**:
1. Cek semua field required terisi
2. Cek format email valid
3. Cek nomor telepon valid
4. Lihat pesan error di bawah field

### Email Verification
**Problem**: Email verifikasi tidak diterima
**Solution**:
1. Cek folder spam
2. Cek email address benar
3. Klik "Kirim ulang"
4. Tunggu beberapa menit
5. Contact admin jika masih bermasalah

## Related Files
- `app/Http/Livewire/UpdateProfileInformationForm.php`
- `resources/views/profile/show.blade.php`
- `resources/views/profile/update-profile-information-form.blade.php`
- `resources/views/profile/update-password-form.blade.php`
- `app/Actions/Fortify/PasswordValidationRules.php`
- `routes/web.php`

## Configuration
File: `config/fortify.php`
```php
'features' => [
    Features::updateProfileInformation(),
    Features::updatePasswords(),
    Features::emailVerification(),
    // Features::twoFactorAuthentication(),
],
```

## Notes
- Profile photo disimpan di `storage/app/public/profile-photos`
- Symbolic link harus dibuat: `php artisan storage:link`
- Email verification opsional, bisa diaktifkan di config
- Two factor authentication disabled by default
