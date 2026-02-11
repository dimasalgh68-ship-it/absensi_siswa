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
        $users = User::where('group', 'user')->get();
        return view('admin.tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'required|in:all_users,specific_users',
            'selected_users' => 'required_if:assigned_to,specific_users|array',
            'due_date' => 'required|date|after:now',
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
            'created_by' => auth()->id,
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

        return redirect()->route('admin.tasks')->with('success', 'Task created successfully!');
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
        $users = User::where('group', 'user')->get();
        $task->load('assignments');
        return view('admin.tasks.edit', compact('task', 'users'));
    }

    public function update(Request $request, Task $task)
    {
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

        return redirect()->route('admin.tasks')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        // Delete associated files
        if ($task->image_path) {
            Storage::disk('public')->delete($task->image_path);
        }

        // Delete submissions and their files
        foreach ($task->submissions as $submission) {
            if ($submission->image_path) {
                Storage::disk('public')->delete($submission->image_path);
            }
        }

        $task->delete();

        return redirect()->route('admin.tasks')->with('success', 'Task deleted successfully!');
    }

    public function updateSubmissionStatus(Task $task, TaskSubmission $submission, Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $submission->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Submission status updated successfully!');
    }
}
