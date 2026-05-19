# Subject (Mata Pelajaran) CRUD - Files Location

## Complete File Structure

### 1. Livewire Component
**Location**: `app/Livewire/Admin/MasterData/SubjectComponent.php`

**Functionality**:
- Create subjects with name and code
- Edit existing subjects
- Delete subjects
- Admin-only authorization
- Validation for unique name and code

**Key Methods**:
- `showCreating()` - Show create modal
- `create()` - Create new subject
- `edit($id)` - Show edit modal
- `update()` - Update subject
- `confirmDeletion($id, $name)` - Show delete confirmation
- `delete()` - Delete subject
- `render()` - Render view with all subjects

---

### 2. Livewire View (Component Template)
**Location**: `resources/views/livewire/admin/master-data/subject.blade.php`

**Features**:
- Table display of all subjects
- Add button to create new subject
- Edit button for each subject
- Delete button with confirmation
- Create modal with code and name fields
- Edit modal with code and name fields
- Delete confirmation modal

**UI Elements**:
- Header with "Data Mata Pelajaran" title
- "Tambah Mata Pelajaran" button
- Table with columns: Kode, Nama Mata Pelajaran, Actions
- Dialog modals for create/edit/delete

---

### 3. Master Data Page View
**Location**: `resources/views/admin/master-data/subject.blade.php`

**Purpose**: Wrapper page that includes the Livewire component

**Content**:
- Admin layout wrapper
- Page header with icon and description
- Livewire component integration

---

### 4. Controller Method
**Location**: `app/Http/Controllers/Admin/MasterDataController.php`

**Added Method**:
```php
public function subject()
{
    return view('admin.master-data.subject');
}
```

---

### 5. Route Definition
**Location**: `routes/web.php`

**Added Route**:
```php
Route::get('/masterdata/subject', [MasterDataController::class, 'subject'])->name('admin.masters.subject');
```

**Route Name**: `admin.masters.subject`
**URL**: `/admin/masterdata/subject`

---

### 6. Sidebar Menu Link
**Location**: `resources/views/layouts/partials/admin-sidebar.blade.php`

**Added Menu Item**:
```blade
<a class="sub-nav-link {{ request()->routeIs('admin.masters.subject') ? 'active' : '' }}" 
   href="{{ route('admin.masters.subject') }}">
   Mata Pelajaran
</a>
```

**Menu Path**: Admin Sidebar → Master Data → Mata Pelajaran

---

## File Summary Table

| File | Type | Purpose |
|------|------|---------|
| `app/Livewire/Admin/MasterData/SubjectComponent.php` | Component | CRUD logic |
| `resources/views/livewire/admin/master-data/subject.blade.php` | View | Component template |
| `resources/views/admin/master-data/subject.blade.php` | View | Page wrapper |
| `app/Http/Controllers/Admin/MasterDataController.php` | Controller | Route handler |
| `routes/web.php` | Route | URL definition |
| `resources/views/layouts/partials/admin-sidebar.blade.php` | View | Menu link |

---

## Access Points

### 1. Via Menu
Admin Dashboard → Master Data → Mata Pelajaran

### 2. Via URL
```
http://localhost:8000/admin/masterdata/subject
```

### 3. Via Route Name
```php
route('admin.masters.subject')
```

---

## Database Model

**Model**: `app/Models/Subject.php`

**Table**: `subjects`

**Fields**:
- `id` - Primary key
- `name` - Subject name (unique, required)
- `code` - Subject code (unique, required, max 10)
- `created_at` - Timestamp
- `updated_at` - Timestamp

**Relationships**:
- `materials()` - One-to-many with Material
- `schedules()` - One-to-many with Schedule
- `exams()` - One-to-many with Exam

---

## Security Features

✅ **Admin-Only Access**
- All CRUD operations check `isNotAdmin`
- Returns 403 Forbidden for unauthorized users
- Teachers and students cannot access

✅ **Validation**
- Subject name must be unique
- Subject code must be unique
- Code limited to 10 characters
- Both fields required

✅ **Authorization Checks**
- `create()` method checks admin status
- `update()` method checks admin status
- `delete()` method checks admin status
- `edit()` method checks admin status

---

## Usage Examples

### Create Subject
1. Go to `/admin/masterdata/subject`
2. Click "Tambah Mata Pelajaran"
3. Enter code (e.g., MTK)
4. Enter name (e.g., Matematika)
5. Click "Confirm"

### Edit Subject
1. Go to `/admin/masterdata/subject`
2. Click "Edit" on subject row
3. Modify code and/or name
4. Click "Confirm"

### Delete Subject
1. Go to `/admin/masterdata/subject`
2. Click "Delete" on subject row
3. Confirm in modal
4. Click "Confirm"

---

## Testing Checklist

- [ ] Access `/admin/masterdata/subject` as admin
- [ ] Create new subject with code and name
- [ ] Edit existing subject
- [ ] Delete subject with confirmation
- [ ] Verify unique name validation
- [ ] Verify unique code validation
- [ ] Try accessing as teacher (should get 403)
- [ ] Try accessing as student (should get 403)
- [ ] Verify menu appears in admin sidebar
- [ ] Verify menu is active when on subject page

---

**Status**: ✅ COMPLETE
**Date**: 2026-05-17
**Access Level**: Admin Only
