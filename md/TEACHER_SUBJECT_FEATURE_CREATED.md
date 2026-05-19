# Teacher Subject Management Feature - Created

## Summary
Created a complete feature to manage which subjects each teacher teaches. This allows admins to assign one or more subjects to each teacher.

---

## Files Created/Modified

### 1. Livewire Component
**File**: `app/Livewire/Admin/TeacherSubjectComponent.php`

**Features**:
- ✅ Display all teachers with their assigned subjects
- ✅ Select a teacher to manage their subjects
- ✅ Toggle subjects on/off for a teacher
- ✅ Save subject assignments
- ✅ Admin-only authorization
- ✅ Success/error messages

**Key Methods**:
- `selectTeacher($teacherId)` - Load teacher and their subjects
- `toggleSubject($subjectId)` - Add/remove subject from selection
- `saveSubjects()` - Save subject assignments using sync()
- `closeModal()` - Close the management modal

### 2. Livewire View
**File**: `resources/views/livewire/admin/teacher-subject.blade.php`

**Features**:
- ✅ Table showing all teachers and their subjects
- ✅ "Kelola" button for each teacher
- ✅ Modal dialog to select subjects
- ✅ Checkbox list of all available subjects
- ✅ Save and Cancel buttons

### 3. Page View
**File**: `resources/views/admin/teacher-subjects.blade.php`

**Features**:
- ✅ Admin layout wrapper
- ✅ Page header with icon and description
- ✅ Livewire component integration

### 4. Model Updates

**Teacher Model** - `app/Models/Teacher.php`
```php
public function subjects()
{
    return $this->belongsToMany(Subject::class, 'teacher_subject');
}
```

**Subject Model** - `app/Models/Subject.php`
```php
public function teachers()
{
    return $this->belongsToMany(Teacher::class, 'teacher_subject');
}
```

### 5. Route Addition
**File**: `routes/web.php`

```php
Route::get('/teacher-subjects', function () {
    return view('admin.teacher-subjects');
})->name('admin.teacher-subjects');
```

### 6. Sidebar Menu
**File**: `resources/views/layouts/partials/admin-sidebar.blade.php`

Added menu item:
```blade
<li class="nav-item {{ request()->routeIs('admin.teacher-subjects') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.teacher-subjects') }}">
        <i class="fas fa-fw fa-book"></i>
        <span>Mata Pelajaran Guru</span>
    </a>
</li>
```

---

## Database Schema

### Existing Migration
**File**: `database/migrations/2026_05_17_create_teacher_subject_table.php`

**Table**: `teacher_subject`

**Fields**:
- `id` - Primary key
- `teacher_id` - Foreign key to teachers table (ULID)
- `subject_id` - Foreign key to subjects table
- `created_at` - Timestamp
- `updated_at` - Timestamp
- `unique(['teacher_id', 'subject_id'])` - Ensures no duplicate assignments

---

## Access Control

### Admin Only
- ✅ View teacher subjects list: `/admin/teacher-subjects`
- ✅ Manage teacher subjects
- ✅ Assign subjects to teachers
- ✅ Remove subjects from teachers

### Teacher/Student
- ❌ Cannot access teacher subject management
- ❌ Cannot assign subjects

---

## Usage

### Access the Feature
```
http://localhost:8000/admin/teacher-subjects
```

### Assign Subjects to a Teacher
1. Go to `/admin/teacher-subjects`
2. Find the teacher in the list
3. Click "Kelola" button
4. Check/uncheck subjects in the modal
5. Click "Save"

### View Teacher's Subjects
- Teachers are displayed in a table
- Current subjects are shown as blue badges
- "Belum ada mata pelajaran" message if no subjects assigned

---

## Relationships

### Many-to-Many Relationship
```
Teacher ←→ Subject
(via teacher_subject pivot table)
```

### Usage in Code
```php
// Get all subjects for a teacher
$teacher->subjects()->get();

// Get all teachers for a subject
$subject->teachers()->get();

// Assign subjects to a teacher
$teacher->subjects()->sync([1, 2, 3]);

// Add a subject to a teacher
$teacher->subjects()->attach($subjectId);

// Remove a subject from a teacher
$teacher->subjects()->detach($subjectId);
```

---

## Features

✅ **Teacher Display**
- Shows teacher name from user relationship
- Displays all assigned subjects as badges
- Shows "Belum ada mata pelajaran" if none assigned

✅ **Subject Management**
- Modal dialog for easy management
- Checkbox list of all available subjects
- Pre-selected subjects are checked
- Save button to persist changes

✅ **Authorization**
- Only admins can access the feature
- Returns 403 Forbidden for unauthorized users

✅ **User Experience**
- Success message after saving
- Error messages for failures
- Clean, intuitive interface
- Responsive design

---

## Testing Checklist

- [ ] Access `/admin/teacher-subjects` as admin
- [ ] See list of all teachers with their subjects
- [ ] Click "Kelola" button on a teacher
- [ ] Modal opens with subject checkboxes
- [ ] Check/uncheck subjects
- [ ] Click "Save" to persist changes
- [ ] Verify subjects are updated in the table
- [ ] Try accessing as teacher (should get 403)
- [ ] Try accessing as student (should get 403)
- [ ] Verify menu appears in admin sidebar
- [ ] Verify menu is active when on the page

---

## Related Features

This feature integrates with:
- **Teacher Management** - `/admin/teachers`
- **Subject Management** - `/admin/masterdata/subject`
- **Materials** - Uses subject to organize materials
- **Schedules** - Uses subject for class schedules
- **Exams** - Uses subject for exam organization
- **Grades** - Uses subject for grade recording

---

**Status**: ✅ COMPLETE
**Date**: 2026-05-17
**Access Level**: Admin Only
**Database**: Uses existing `teacher_subject` migration
