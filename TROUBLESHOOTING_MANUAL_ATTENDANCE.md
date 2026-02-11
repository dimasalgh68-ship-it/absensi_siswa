# Troubleshooting: Fitur Absensi Manual

## Checklist Debug

### 1. Pastikan Filter Per Hari Aktif
- [ ] Buka halaman `/admin/attendances`
- [ ] Pilih filter **Per Hari** (bukan Per Bulan atau Per Minggu)
- [ ] Pilih tanggal hari ini atau tanggal tertentu
- [ ] Tombol "Absen" hanya muncul di filter Per Hari

### 2. Pastikan Siswa Belum Absen
- [ ] Tombol "Absen" hanya muncul untuk siswa yang **belum ada data absensi**
- [ ] Jika siswa sudah absen, akan muncul tombol Edit & Hapus
- [ ] Cek di database: `SELECT * FROM attendances WHERE user_id = X AND date = 'YYYY-MM-DD'`

### 3. Cek Browser Console
Buka Developer Tools (F12) → Console tab:
- [ ] Cek apakah ada error JavaScript
- [ ] Cek apakah Livewire loaded: ketik `Livewire` di console, harus return object
- [ ] Cek apakah ada error Livewire

### 4. Cek Network Tab
Buka Developer Tools (F12) → Network tab:
- [ ] Klik tombol "Absen"
- [ ] Cek apakah ada request ke `/livewire/update`
- [ ] Cek response: apakah ada error?

### 5. Cek Laravel Log
```bash
# Windows
Get-Content storage/logs/laravel.log -Tail 50

# Linux/Mac
tail -f storage/logs/laravel.log
```

Cari error terkait:
- `openCreateModal`
- `createAttendance`
- `AttendanceComponent`

### 6. Test Manual di Tinker
```bash
php artisan tinker
```

```php
// Test apakah method ada
$component = new App\Livewire\Admin\AttendanceComponent();
method_exists($component, 'openCreateModal'); // Should return true
method_exists($component, 'createAttendance'); // Should return true

// Test create attendance
$user = App\Models\User::where('group', 'student')->first();
$attendance = App\Models\Attendance::create([
    'user_id' => $user->id,
    'date' => today(),
    'time_in' => '08:00',
    'status' => 'present',
    'validation_method' => 'manual',
]);
```

### 7. Cek Livewire Component
```bash
php artisan livewire:list
```

Pastikan `App\Livewire\Admin\AttendanceComponent` ada di list.

### 8. Clear All Cache
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## Common Issues

### Issue 1: Tombol Tidak Muncul

**Symptom:** Kolom Aksi kosong atau hanya ada "-"

**Possible Causes:**
1. Filter bukan Per Hari (masih Per Bulan/Minggu)
2. Siswa sudah punya data absensi
3. Variable `$isPerDayFilter` salah

**Solution:**
```php
// Cek di view
@if ($isPerDayFilter)
    <p>Filter Per Hari: AKTIF</p>
@else
    <p>Filter Per Hari: TIDAK AKTIF</p>
@endif

@if ($attendance)
    <p>Siswa sudah absen</p>
@else
    <p>Siswa belum absen - tombol harus muncul</p>
@endif
```

### Issue 2: Tombol Muncul Tapi Tidak Bisa Diklik

**Symptom:** Tombol ada tapi klik tidak ada response

**Possible Causes:**
1. Livewire tidak loaded
2. JavaScript error
3. Method `openCreateModal` tidak ada

**Solution:**
1. Cek browser console untuk error
2. Pastikan Livewire scripts loaded:
```html
<!-- Di layout -->
@livewireScripts
```

3. Test Livewire di console:
```javascript
// Di browser console
Livewire.emit('openCreateModal', 1);
```

### Issue 3: Modal Tidak Muncul

**Symptom:** Klik tombol, tidak ada error, tapi modal tidak muncul

**Possible Causes:**
1. Property `showCreateModal` tidak ter-update
2. Modal component tidak ada
3. CSS issue (modal ada tapi hidden)

**Solution:**
1. Cek di browser DevTools → Elements:
   - Cari element dengan `wire:model="showCreateModal"`
   - Cek apakah ada class `hidden` atau `display: none`

2. Test manual set property:
```javascript
// Di browser console
Livewire.find('component-id').set('showCreateModal', true);
```

3. Tambah debug di view:
```blade
<div>
    showCreateModal: {{ $showCreateModal ? 'true' : 'false' }}
</div>
```

### Issue 4: Validation Error

**Symptom:** Modal muncul, isi form, submit, ada error

**Possible Causes:**
1. Required field kosong
2. Format waktu salah
3. User ID tidak valid

**Solution:**
Cek validation error di view:
```blade
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

### Issue 5: Data Tidak Tersimpan

**Symptom:** Submit berhasil, modal tutup, tapi data tidak ada di database

**Possible Causes:**
1. Transaction rollback
2. Validation gagal silent
3. Cache issue

**Solution:**
1. Cek database langsung:
```sql
SELECT * FROM attendances 
WHERE user_id = X 
AND date = 'YYYY-MM-DD' 
ORDER BY created_at DESC 
LIMIT 1;
```

2. Cek Laravel log untuk error
3. Clear cache:
```bash
php artisan cache:clear
```

## Debug Steps

### Step 1: Verify Component Loaded
```blade
<!-- Add to top of view -->
<div class="bg-yellow-100 p-4 mb-4">
    <p>Component: {{ get_class($this) }}</p>
    <p>isPerDayFilter: {{ $isPerDayFilter ? 'YES' : 'NO' }}</p>
    <p>showCreateModal: {{ $showCreateModal ? 'YES' : 'NO' }}</p>
</div>
```

### Step 2: Verify Button Renders
```blade
<!-- In action column -->
@if ($attendance)
    <p class="text-green-600">HAS ATTENDANCE</p>
@else
    <p class="text-red-600">NO ATTENDANCE - BUTTON SHOULD SHOW</p>
    <button wire:click="openCreateModal({{ $employee->id }})" class="bg-blue-500 text-white px-4 py-2">
        TEST BUTTON
    </button>
@endif
```

### Step 3: Verify Method Called
```php
// In AttendanceComponent.php
public function openCreateModal($userId)
{
    \Log::info('openCreateModal called', ['userId' => $userId]);
    
    $this->createUserId = $userId;
    $this->createDate = $this->date ?? today()->format('Y-m-d');
    $this->showCreateModal = true;
    
    \Log::info('Modal should open', ['showCreateModal' => $this->showCreateModal]);
}
```

### Step 4: Check Modal Visibility
```blade
<!-- Add to modal -->
<x-dialog-modal wire:model="showCreateModal">
    <x-slot name="title">
        DEBUG: Modal Opened! User ID: {{ $createUserId }}
    </x-slot>
    <!-- rest of modal -->
</x-dialog-modal>
```

## Quick Fix Commands

```bash
# Clear everything
php artisan optimize:clear

# Restart server (if using artisan serve)
# Ctrl+C then
php artisan serve

# Check Livewire version
composer show livewire/livewire

# Reinstall Livewire (if needed)
composer require livewire/livewire --with-all-dependencies
```

## Contact Points

If still not working, check:
1. ✅ Livewire version compatibility
2. ✅ Browser compatibility (try different browser)
3. ✅ JavaScript conflicts (disable other scripts)
4. ✅ Server logs (storage/logs/laravel.log)
5. ✅ Database connection

## Expected Behavior

**When Working Correctly:**
1. Filter Per Hari → Tombol "Absen" muncul untuk siswa yang belum absen
2. Klik tombol → Modal "Tambah Absensi Manual" muncul
3. Isi form → Submit → Success message
4. Data tersimpan di database dengan `validation_method = 'manual'`
5. Tombol "Absen" hilang, diganti tombol Edit & Hapus
