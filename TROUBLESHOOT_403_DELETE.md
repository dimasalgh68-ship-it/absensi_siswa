# 🔒 Troubleshooting Error 403 - Hapus Siswa

## ❌ Error: "403 Dilarang"

Jika muncul error ini saat mencoba menghapus siswa, berikut solusinya:

## 🔍 Penyebab Umum

### 1. Login Bukan Sebagai Admin

**Masalah:** User yang login bukan admin/superadmin

**Solusi:** Login dengan akun admin

```
Superadmin:
Email: superadmin@example.com
Password: superadmin

Admin:
Email: admin@example.com  
Password: admin
```

### 2. Mencoba Hapus Admin Lain

**Masalah:** Admin biasa mencoba hapus admin/superadmin lain

**Solusi:** Hanya superadmin yang bisa hapus admin lain

### 3. Session Expired

**Masalah:** Session login sudah expired

**Solusi:** Logout dan login ulang

## 📋 Permission Matrix

| User Role | Bisa Hapus Student | Bisa Hapus Teacher | Bisa Hapus Admin | Bisa Hapus Superadmin |
|-----------|-------------------|-------------------|-----------------|---------------------|
| Superadmin | ✅ Yes | ✅ Yes | ✅ Yes | ✅ Yes |
| Admin | ✅ Yes | ✅ Yes | ❌ No | ❌ No |
| Teacher | ❌ No | ❌ No | ❌ No | ❌ No |
| Student | ❌ No | ❌ No | ❌ No | ❌ No |

## 🔧 Cara Debug

### 1. Cek Role User yang Login

Buka browser console (F12) dan jalankan:

```javascript
// Di halaman admin
console.log('Current user:', window.Laravel?.user);
```

### 2. Cek Log Laravel

```bash
# Windows
type storage\logs\laravel.log | Select-String "Delete user forbidden" -Context 5

# Atau buka file langsung
notepad storage\logs\laravel.log
```

Log akan menampilkan:
```
Delete user forbidden
current_user_id: 123
current_user_group: student  <- Ini masalahnya!
target_user_id: 456
target_user_group: student
```

### 3. Verifikasi di Database

```sql
-- Cek user yang sedang login
SELECT id, name, email, `group` FROM users WHERE id = [your_user_id];

-- Cek semua admin
SELECT id, name, email, `group` FROM users WHERE `group` IN ('admin', 'superadmin');
```

## ✅ Solusi Cepat

### Opsi 1: Login Sebagai Superadmin

1. Logout dari akun saat ini
2. Login dengan:
   ```
   Email: superadmin@example.com
   Password: superadmin
   ```
3. Coba hapus siswa lagi

### Opsi 2: Ubah Role User di Database

**⚠️ Hanya untuk development/testing!**

```sql
-- Ubah user menjadi admin
UPDATE users SET `group` = 'admin' WHERE email = 'your@email.com';

-- Atau ubah menjadi superadmin
UPDATE users SET `group` = 'superadmin' WHERE email = 'your@email.com';
```

Setelah itu:
1. Logout
2. Login ulang
3. Coba hapus siswa

### Opsi 3: Buat Admin Baru via Tinker

```bash
php artisan tinker
```

```php
$admin = new App\Models\User();
$admin->name = 'Admin Baru';
$admin->email = 'newadmin@example.com';
$admin->password = Hash::make('password');
$admin->raw_password = 'password';
$admin->group = 'admin';
$admin->phone = '08123456789';
$admin->gender = 'male';
$admin->save();
```

Login dengan:
```
Email: newadmin@example.com
Password: password
```

## 🧪 Test Permission

Setelah login sebagai admin, test dengan:

1. **Buka halaman Data Siswa**
2. **Klik tombol Delete** pada siswa
3. **Konfirmasi** di modal
4. **Harusnya berhasil** tanpa error 403

## 📝 Catatan Penting

### Hierarki Permission

```
Superadmin (paling tinggi)
    ↓
Admin
    ↓
Teacher
    ↓
Student (paling rendah)
```

### Aturan Delete

1. **Superadmin** → Bisa hapus siapa saja
2. **Admin** → Bisa hapus teacher, student, user (tidak bisa hapus admin/superadmin lain)
3. **Teacher** → Tidak bisa hapus siapa-siapa
4. **Student** → Tidak bisa hapus siapa-siapa

### Self-Delete

- Admin **BISA** hapus diri sendiri
- Superadmin **BISA** hapus diri sendiri
- Teacher **TIDAK BISA** hapus diri sendiri
- Student **TIDAK BISA** hapus diri sendiri

## 🆘 Masih Error?

### Cek Middleware

Pastikan middleware admin sudah benar di route:

```php
// routes/web.php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/employees', EmployeeComponent::class);
});
```

### Clear Cache

```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
```

### Restart Server

```bash
# Stop server (Ctrl+C)
php artisan serve
```

### Cek Session

```bash
# Clear session
php artisan session:flush

# Atau hapus manual
del storage\framework\sessions\*
```

## 📞 Quick Commands

```bash
# Cek log error
type storage\logs\laravel.log

# Clear cache
php artisan optimize:clear

# Restart server
php artisan serve

# Buat admin baru
php artisan tinker
```

## ✨ Kesimpulan

Error 403 biasanya terjadi karena:
1. ❌ Login bukan sebagai admin
2. ❌ Admin mencoba hapus admin lain
3. ❌ Session expired

**Solusi tercepat:** Login sebagai superadmin!

---

**Status:** ✅ Permission sudah diperbaiki!

**Login Admin:**
- Email: `superadmin@example.com`
- Password: `superadmin`
