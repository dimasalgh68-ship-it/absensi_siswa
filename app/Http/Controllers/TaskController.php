<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index()
    {
        return view('admin.tasks.index');
    }

    public function create()
    {
        // SECURITY BUG FIX: Only admin can create tasks
        if (!auth()->user()->isAdmin) {
            abort(403, 'Anda tidak memiliki izin untuk membuat tugas.');
        }

        $users = User::where('group', 'user')->get();
        return view('admin.tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        // SECURITY BUG FIX: Only admin can create tasks
        if (!auth()->user()->isAdmin) {
            abort(403, 'Anda tidak memiliki izin untuk membuat tugas.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'required|in:all_users,specific_users',
            'selected_users' => 'required_if:assigned_to,specific_users|array',
            'due_date' => 'required|date|after_or_equal:today',  // BUG FIX: Use timezone-aware validation
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tasks', 'public');
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'due_date' => $request->due_date,
            'created_by' => auth()->id(),  // BUG FIX: Added parentheses to call the method
            'image_path' => $imagePath,
            'link' => $request->link,
            'status' => 'active',
        ]);

        if ($request->assigned_to === 'specific_users' && $request->selected_users) {
            $task->assignments()->createMany(
                collect($request->selected_users)->map(function ($userId) {
                    return ['user_id' => $userId];
                })
            );
        }

        return redirect()->route(auth()->user()->isTeacher ? 'teacher.tasks' : 'admin.tasks')->with('success', 'Task created successfully!');
    }

    public function show(Task $task)
    {
        $task->load(['creator', 'assignments.user', 'submissions.user']);

        // Get users who haven't submitted
        if ($task->assigned_to === 'all_users') {
            $allUsers = User::where('group', 'user')->get();
            $submittedUserIds = $task->submissions->pluck('user_id')->toArray();
            $nonSubmitters = $allUsers->filter(function ($user) use ($submittedUserIds) {
                return !in_array($user->id, $submittedUserIds);
            });
        } else {
            $assignedUserIds = $task->assignments->pluck('user_id')->toArray();
            $submittedUserIds = $task->submissions->pluck('user_id')->toArray();
            $nonSubmittedUserIds = array_diff($assignedUserIds, $submittedUserIds);
            $nonSubmitters = User::whereIn('id', $nonSubmittedUserIds)->get();
        }

        return view('admin.tasks.show', compact('task', 'nonSubmitters'));
    }

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

    public function update(Request $request, Task $task)
    {
        // SECURITY BUG FIX: Only task creator or admin can update
        if (auth()->user()->id !== $task->created_by && !auth()->user()->isAdmin) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah tugas ini.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'required|in:all_users,specific_users',
            'selected_users' => 'required_if:assigned_to,specific_users|array',
            'due_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
        ]);

        $imagePath = $task->image_path;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($task->image_path) {
                Storage::disk('public')->delete($task->image_path);
            }
            $imagePath = $request->file('image')->store('tasks', 'public');
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'due_date' => $request->due_date,
            'image_path' => $imagePath,
            'link' => $request->link,
        ]);

        // Update assignments
        $task->assignments()->delete(); // Remove old assignments
        if ($request->assigned_to === 'specific_users' && $request->selected_users) {
            $task->assignments()->createMany(
                collect($request->selected_users)->map(function ($userId) {
                    return ['user_id' => $userId];
                })
            );
        }

        return redirect()->route(auth()->user()->isTeacher ? 'teacher.tasks' : 'admin.tasks')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        // CRITICAL BUG FIX #1: Add authorization check
        // Only task creator or admin can delete
        if (auth()->user()->id !== $task->created_by && !auth()->user()->isAdmin) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus tugas ini.');
        }

        // Delete associated files
        if ($task->image_path) {
            Storage::disk('public')->delete($task->image_path);
        }

        // Delete submissions and their files
        // BUG FIX: Also delete submission records (cascade delete)
        foreach ($task->submissions as $submission) {
            if ($submission->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }
            $submission->delete();  // Explicitly delete submission record
        }

        $task->delete();

        return redirect()->route(auth()->user()->isTeacher ? 'teacher.tasks' : 'admin.tasks')->with('success', 'Task deleted successfully!');
    }

    public function updateSubmissionStatus(Task $task, TaskSubmission $submission, Request $request)
    {
        // CRITICAL BUG FIX #2: Add authorization check
        // Only task creator or admin can update submission status
        if (auth()->user()->id !== $task->created_by && !auth()->user()->isAdmin) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah status pengumpulan ini.');
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $submission->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Submission status updated successfully!');
    }
}
