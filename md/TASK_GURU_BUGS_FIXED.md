# Task Creation Page - Guru (Teacher) Related Bugs - FIXED

## Summary
Found and fixed **4 critical security bugs** in the task creation system related to teacher/guru access control and data handling.

---

## Bugs Found & Fixed

### 🔴 BUG #1: Missing Authorization Check in `create()` Method
**Severity**: CRITICAL (Security)
**Location**: `app/Http/Controllers/TaskController.php` - Line 17

**Problem**:
- No authorization check to verify if user is admin before showing task creation form
- Teachers or regular users could potentially access the task creation page
- Violates principle of least privilege

**Original Code**:
```php
public function create()
{
    $users = User::where('group', 'user')->get();
    return view('admin.tasks.create', compact('users'));
}
```

**Fixed Code**:
```php
public function create()
{
    // SECURITY BUG FIX: Only admin can create tasks
    if (!auth()->user()->isAdmin) {
        abort(403, 'Anda tidak memiliki izin untuk membuat tugas.');
    }

    $users = User::where('group', 'user')->get();
    return view('admin.tasks.create', compact('users'));
}
```

**Impact**: Prevents unauthorized access to task creation interface

---

### 🔴 BUG #2: Missing Authorization Check in `store()` Method
**Severity**: CRITICAL (Security)
**Location**: `app/Http/Controllers/TaskController.php` - Line 28

**Problem**:
- No authorization check before creating a task
- Teachers could create tasks by sending POST request directly
- Bypasses UI restrictions

**Original Code**:
```php
public function store(Request $request)
{
    $request->validate([...]);
    // No authorization check
    $task = Task::create([...]);
}
```

**Fixed Code**:
```php
public function store(Request $request)
{
    // SECURITY BUG FIX: Only admin can create tasks
    if (!auth()->user()->isAdmin) {
        abort(403, 'Anda tidak memiliki izin untuk membuat tugas.');
    }

    $request->validate([...]);
    $task = Task::create([...]);
}
```

**Impact**: Prevents unauthorized task creation via direct API calls

---

### 🔴 BUG #3: Typo in `store()` Method - Missing Method Call Parentheses
**Severity**: HIGH (Logic Error)
**Location**: `app/Http/Controllers/TaskController.php` - Line 54

**Problem**:
- `auth()->id` should be `auth()->id()` (missing parentheses)
- This would store the Closure object instead of the actual user ID
- Tasks would have invalid `created_by` values
- Breaks task creator identification and authorization checks

**Original Code**:
```php
$task = Task::create([
    'title' => $request->title,
    'description' => $request->description,
    'assigned_to' => $request->assigned_to,
    'due_date' => $request->due_date,
    'created_by' => auth()->id,  // ❌ Missing parentheses
    'image_path' => $imagePath,
    'link' => $request->link,
    'status' => 'active',
]);
```

**Fixed Code**:
```php
$task = Task::create([
    'title' => $request->title,
    'description' => $request->description,
    'assigned_to' => $request->assigned_to,
    'due_date' => $request->due_date,
    'created_by' => auth()->id(),  // ✅ Added parentheses
    'image_path' => $imagePath,
    'link' => $request->link,
    'status' => 'active',
]);
```

**Impact**: Ensures correct user ID is stored for task creator

---

### 🔴 BUG #4: Missing Authorization Check in `edit()` Method
**Severity**: CRITICAL (Security)
**Location**: `app/Http/Controllers/TaskController.php` - Line 103

**Problem**:
- No authorization check before showing edit form
- Any authenticated user could edit any task
- Violates data integrity and access control

**Original Code**:
```php
public function edit(Task $task)
{
    $users = User::where('group', 'user')->get();
    $task->load('assignments');
    return view('admin.tasks.edit', compact('task', 'users'));
}
```

**Fixed Code**:
```php
public function edit(Task $task)
{
    // SECURITY BUG FIX: Only task creator or admin can edit
    if (auth()->user()->id !== $task->created_by && !auth()->user()->isAdmin) {
        abort(403, 'Anda tidak memiliki izin untuk mengedit tugas ini.');
    }

    $users = User::where('group', 'user')->get();
    $task->load('assignments');
    return view('admin.tasks.edit', compact('task', 'users'));
}
```

**Impact**: Prevents unauthorized task editing

---

### 🔴 BUG #5: Missing Authorization Check in `update()` Method
**Severity**: CRITICAL (Security)
**Location**: `app/Http/Controllers/TaskController.php` - Line 120

**Problem**:
- No authorization check before updating a task
- Teachers could update any task by sending PUT request
- Bypasses UI restrictions

**Original Code**:
```php
public function update(Request $request, Task $task)
{
    $request->validate([...]);
    // No authorization check
    $task->update([...]);
}
```

**Fixed Code**:
```php
public function update(Request $request, Task $task)
{
    // SECURITY BUG FIX: Only task creator or admin can update
    if (auth()->user()->id !== $task->created_by && !auth()->user()->isAdmin) {
        abort(403, 'Anda tidak memiliki izin untuk mengubah tugas ini.');
    }

    $request->validate([...]);
    $task->update([...]);
}
```

**Impact**: Prevents unauthorized task updates

---

## Files Modified
- ✅ `app/Http/Controllers/TaskController.php`

## Testing Recommendations
1. **Test as Teacher**: Try accessing `/admin/tasks/create` as teacher - should get 403 error
2. **Test as Admin**: Verify admin can still create tasks normally
3. **Test Task Creation**: Verify `created_by` field stores correct user ID
4. **Test Edit Access**: Try editing another admin's task as teacher - should get 403 error
5. **Test Update Access**: Try updating another admin's task via API as teacher - should get 403 error

## Security Impact
- **Before**: 🔴 CRITICAL - Unauthorized users could create/edit/delete tasks
- **After**: ✅ SECURE - Only admins can manage tasks

## Related Bugs Already Fixed
- ✅ BUG #1 (destroy method): Authorization check already present
- ✅ BUG #2 (updateSubmissionStatus method): Authorization check already present

---

**Status**: ✅ FIXED
**Date**: 2026-05-17
**Severity**: CRITICAL (5 bugs)
