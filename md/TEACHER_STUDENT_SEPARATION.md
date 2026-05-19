# ✅ TEACHER & STUDENT SEPARATION - COMPLETE

## Overview
Guru dan Siswa sekarang memiliki model dan tabel terpisah dari User. Ini memungkinkan manajemen data yang lebih baik dan struktur database yang lebih jelas.

## Architecture

### Before (Monolithic)
```
User Table
├── group = 'user'
├── group = 'admin'
├── group = 'superadmin'
├── group = 'teacher'  ← Dicampur dengan user
└── group = 'student'  ← Dicampur dengan user
```

### After (Separated)
```
User Table (Base User)
├── group = 'user'
├── group = 'admin'
├── group = 'superadmin'
├── group = 'teacher'  → Teacher Table
└── group = 'student'  → Student Table

Teacher Table (Specific Teacher Data)
├── user_id (FK)
├── nip (Nomor Induk Pegawai)
├── specialization
├── certification_number
├── certification_date
└── status

Student Table (Specific Student Data)
├── user_id (FK)
├── nis (Nomor Induk Siswa)
├── class
├── status
└── enrollment_date
```

## Database Changes

### 1. Users Table Modification
**File**: `2026_05_17_add_teacher_student_groups.php`
- ✅ Menambahkan 'teacher' dan 'student' ke enum group
- ✅ Enum sebelumnya: ['user', 'admin', 'superadmin']
- ✅ Enum sekarang: ['user', 'admin', 'superadmin', 'teacher', 'student']

### 2. Teachers Table Creation
**File**: `2026_05_17_create_teachers_table.php`
```sql
CREATE TABLE teachers (
    id ULID PRIMARY KEY,
    user_id ULID UNIQUE (FK to users),
    nip VARCHAR(255) UNIQUE,
    specialization VARCHAR(255),
    certification_number VARCHAR(255),
    certification_date DATE,
    status ENUM('active', 'inactive', 'on_leave'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

### 3. Students Table Creation
**File**: `2026_05_17_create_students_table.php`
```sql
CREATE TABLE students (
    id ULID PRIMARY KEY,
    user_id ULID UNIQUE (FK to users),
    nis VARCHAR(255) UNIQUE,
    class VARCHAR(255),
    status ENUM('active', 'inactive', 'graduated', 'dropped_out'),
    enrollment_date DATE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

## Models

### Teacher Model
**File**: `app/Models/Teacher.php`
```php
class Teacher extends Model
{
    // Relationships
    public function user() { ... }
    public function materials() { ... }
    public function schedules() { ... }
    public function exams() { ... }
    public function grades() { ... }
    
    // Scopes
    public function scopeActive($query) { ... }
    public function scopeInactive($query) { ... }
}
```

### Student Model
**File**: `app/Models/Student.php`
```php
class Student extends Model
{
    // Relationships
    public function user() { ... }
    public function attendances() { ... }
    public function grades() { ... }
    public function taskSubmissions() { ... }
    
    // Scopes
    public function scopeActive($query) { ... }
    public function scopeInactive($query) { ... }
}
```

### User Model Updates
**File**: `app/Models/User.php`
```php
class User extends Authenticatable
{
    // New relationships
    public function teacher() { ... }
    public function student() { ... }
}
```

## Usage Examples

### Create a Teacher
```php
// Create user first
$user = User::create([
    'name' => 'Budi Santoso',
    'email' => 'budi@example.com',
    'group' => 'teacher',
    // ... other fields
]);

// Create teacher record
$teacher = Teacher::create([
    'user_id' => $user->id,
    'nip' => '123456789',
    'specialization' => 'Matematika',
    'status' => 'active',
]);
```

### Create a Student
```php
// Create user first
$user = User::create([
    'name' => 'Andi Wijaya',
    'email' => 'andi@example.com',
    'group' => 'student',
    // ... other fields
]);

// Create student record
$student = Student::create([
    'user_id' => $user->id,
    'nis' => '987654321',
    'class' => '10A',
    'status' => 'active',
]);
```

### Access Related Data
```php
// From User to Teacher
$user = User::find($id);
$teacher = $user->teacher;
$nip = $teacher->nip;

// From Teacher to User
$teacher = Teacher::find($id);
$userName = $teacher->user->name;
$email = $teacher->user->email;

// Get active teachers
$activeTeachers = Teacher::active()->get();

// Get teacher's materials
$materials = $teacher->materials;
```

## Benefits

| Benefit | Description |
|---------|-------------|
| **Separation of Concerns** | Guru dan siswa memiliki data spesifik mereka sendiri |
| **Better Scalability** | Mudah menambah field spesifik untuk guru/siswa |
| **Cleaner Queries** | Query lebih spesifik dan efisien |
| **Type Safety** | Model terpisah memberikan type hints yang lebih baik |
| **Easier Maintenance** | Kode lebih terorganisir dan mudah dipahami |
| **Flexible Status** | Setiap role memiliki status yang sesuai |

## Migration Status

| Migration | Status | Description |
|-----------|--------|-------------|
| `2026_05_17_add_teacher_student_groups` | ✅ DONE | Add teacher/student to user groups |
| `2026_05_17_create_teachers_table` | ✅ DONE | Create teachers table |
| `2026_05_17_create_students_table` | ✅ DONE | Create students table |

## Next Steps

1. **Update Seeders**
   - Update TeacherSeeder untuk membuat Teacher records
   - Update StudentSeeder untuk membuat Student records

2. **Update Controllers**
   - Update TeacherController untuk menggunakan Teacher model
   - Update StudentController untuk menggunakan Student model

3. **Update Views**
   - Update teacher views untuk menampilkan teacher-specific data
   - Update student views untuk menampilkan student-specific data

4. **Update Imports**
   - Update UsersImport untuk membuat Teacher/Student records
   - Validasi data spesifik guru/siswa

## Files Created/Modified

| File | Type | Status |
|------|------|--------|
| `app/Models/Teacher.php` | Created | ✅ |
| `app/Models/Student.php` | Created | ✅ |
| `app/Models/User.php` | Modified | ✅ |
| `database/migrations/2026_05_17_add_teacher_student_groups.php` | Created | ✅ |
| `database/migrations/2026_05_17_create_teachers_table.php` | Created | ✅ |
| `database/migrations/2026_05_17_create_students_table.php` | Created | ✅ |

## Status
✅ **COMPLETE** - Teacher dan Student sekarang terpisah dari User

---
*Last Updated: 2026-05-17*
