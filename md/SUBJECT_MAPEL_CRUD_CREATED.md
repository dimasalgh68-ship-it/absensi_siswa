# Subject (Mata Pelajaran) CRUD - Created

## Summary
Created a complete Subject (Mata Pelajaran) CRUD system restricted to admin users only.

---

## Files Created

### 1. Livewire Component
**File**: `app/Livewire/Admin/MasterData/SubjectComponent.php`

Features:
- ✅ Create new subjects with name and code
- ✅ Edit existing subjects
- ✅ Delete subjects
- ✅ Admin-only authorization checks on all operations
- ✅ Validation for unique name and code
- ✅ Success/error messages via banner

Security:
- Only admins can create subjects
- Only admins can edit subjects
- Only admins can delete subjects
- Returns 403 error for unauthorized access

### 2. Livewire View
**File**: `resources/views/livewire/admin/master-data/subject.blade.php`

Features:
- ✅ Table display of all subjects with code and name
- ✅ Add button to create new subject
- ✅ Edit button for each subject
- ✅ Delete button with confirmation modal
- ✅ Create modal with code and name fields
- ✅ Edit modal with code and name fields
- ✅ Delete confirmation modal

### 3. Master Data View
**File**: `resources/views/admin/master-data/subject.blade.php`

Features:
- ✅ Admin layout wrapper
- ✅ Page header with icon and description
- ✅ Livewire component integration

### 4. Controller Update
**File**: `app/Http/Controllers/Admin/MasterDataController.php`

Added:
```php
public function subject()
{
    return view('admin.master-data.subject');
}
```

### 5. Route Addition
**File**: `routes/web.php`

Added:
```php
Route::get('/masterdata/subject', [MasterDataController::class, 'subject'])->name('admin.masters.subject');
```

---

## Access Control

### Admin Only
- ✅ View subjects list: `/admin/masterdata/subject`
- ✅ Create subjects
- ✅ Edit subjects
- ✅ Delete subjects

### Teacher/Student
- ❌ Cannot access subject management
- ❌ Cannot create subjects
- ❌ Cannot edit subjects
- ❌ Cannot delete subjects

---

## Database Schema

The Subject model uses the following fields:
- `id` - Primary key
- `name` - Subject name (unique, required)
- `code` - Subject code (unique, required, max 10 chars)
- `created_at` - Timestamp
- `updated_at` - Timestamp

---

## Usage

### Access the Subject Management Page
```
http://localhost:8000/admin/masterdata/subject
```

### Create a Subject
1. Click "Tambah Mata Pelajaran" button
2. Enter subject code (e.g., MTK, IPA, BHS)
3. Enter subject name (e.g., Matematika, Ilmu Pengetahuan Alam)
4. Click "Confirm"

### Edit a Subject
1. Click "Edit" button on the subject row
2. Modify code and/or name
3. Click "Confirm"

### Delete a Subject
1. Click "Delete" button on the subject row
2. Confirm deletion in the modal
3. Click "Confirm"

---

## Security Features

✅ **Authorization Checks**
- All CRUD operations check if user is admin
- Returns 403 Forbidden for unauthorized access

✅ **Validation**
- Subject name must be unique
- Subject code must be unique
- Both fields are required
- Code limited to 10 characters

✅ **Admin-Only Access**
- Route is within admin middleware group
- Component checks `isNotAdmin` on every operation
- No teacher or student access

---

## Related Models

The Subject model has relationships with:
- `Material` - One-to-many (subjects have many materials)
- `Schedule` - One-to-many (subjects have many schedules)
- `Exam` - One-to-many (subjects have many exams)

---

## Testing Recommendations

1. **Test as Admin**
   - ✅ Can access `/admin/masterdata/subject`
   - ✅ Can create new subjects
   - ✅ Can edit subjects
   - ✅ Can delete subjects

2. **Test as Teacher**
   - ❌ Cannot access `/admin/masterdata/subject` (403 error)

3. **Test as Student**
   - ❌ Cannot access `/admin/masterdata/subject` (403 error)

4. **Test Validation**
   - ✅ Cannot create subject with duplicate name
   - ✅ Cannot create subject with duplicate code
   - ✅ Cannot create subject without name
   - ✅ Cannot create subject without code

---

**Status**: ✅ COMPLETED
**Date**: 2026-05-17
**Access Level**: Admin Only
