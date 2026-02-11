<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserAttendanceController extends Controller
{
    public function applyLeave()
    {
        $attendance = Attendance::where('user_id', Auth::user()->id)
            ->where('date', date('Y-m-d'))
            ->first();
        return view('attendances.apply-leave', ['attendance' => $attendance]);
    }

    public function storeLeaveRequest(Request $request)
    {
        $request->validate([
            'status' => ['required', 'in:excused,sick'],
            'note' => ['required', 'string', 'max:255'],
            'from' => ['required', 'date'],
            'to' => ['nullable', 'date', 'after:from'],
            'attachment' => ['nullable', 'file', 'max:3072'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
        ]);
        try {
            // Save new attachment file
            $newAttachment = null;
            if ($request->file('attachment')) {
                $newAttachment = $request->file('attachment')->storePublicly(
                    'attachments',
                    ['disk' => config('jetstream.attachment_disk')]
                );
            }

            $fromDate = Carbon::parse($request->from);
            $fromDate->range($toDate = Carbon::parse($request->to ?? $fromDate))
                ->forEach(function (Carbon $date) use ($request, $newAttachment) {
                    $existing = Attendance::where('user_id', Auth::user()->id)
                        ->where('date', $date->format('Y-m-d'))
                        ->first();

                    if ($existing) {
                        $existing->update([
                            'status' => $request->status,
                            'note' => $request->note,
                            'attachment' => $newAttachment ?? $existing->attachment,
                            'latitude' => doubleval($request->lat) ?? $existing->latitude,
                            'longitude' => doubleval($request->lng) ?? $existing->longitude,
                        ]);
                    } else {
                        Attendance::create([
                            'user_id' => Auth::user()->id,
                            'status' => $request->status,
                            'date' => $date->format('Y-m-d'),
                            'note' => $request->note,
                            'attachment' => $newAttachment ?? null,
                            'latitude' => $request->lat ? doubleval($request->lat) : null,
                            'longitude' => $request->lng ? doubleval($request->lng) : null,
                        ]);
                    }
                });

            Attendance::clearUserAttendanceCache(Auth::user(), $fromDate);
            if (!$fromDate->isSameMonth($toDate)) {
                Attendance::clearUserAttendanceCache(Auth::user(), $toDate);
            }

            return redirect(route('home'))
                ->with('flash.banner', __('Created successfully.'));
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('flash.banner', $th->getMessage())
                ->with('flash.bannerStyle', 'danger');
        }
    }

    public function history()
    {
        return view('attendances.history');
    }

    public function taskDetail(Task $task)
    {
        // Check if user can access this task
        $canAccess = $task->assigned_to === 'all_users' ||
                    $task->assignments()->where('user_id', auth()->id)->exists();

        if (!$canAccess) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        $submission = TaskSubmission::where('task_id', $task->id)
                                   ->where('user_id', auth()->id)
                                   ->first();

        // Check if deadline has passed
        $isPastDeadline = false;
        if ($task->due_date) {
            $isPastDeadline = Carbon::parse($task->due_date)->isPast();
        }

        // Check if submission was late
        $wasLateSubmission = false;
        if ($submission && $task->due_date) {
            $wasLateSubmission = Carbon::parse($submission->submitted_at)->isAfter(Carbon::parse($task->due_date));
        }

        return view('user.task-detail', compact('task', 'submission', 'isPastDeadline', 'wasLateSubmission'));
    }

    public function taskSubmit(Task $task)
    {
        // Check if user can access this task
        $canAccess = $task->assigned_to === 'all_users' ||
                    $task->assignments()->where('user_id', auth()->id)->exists();

        if (!$canAccess) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Check if already submitted
        $existingSubmission = TaskSubmission::where('task_id', $task->id)
                                           ->where('user_id', auth()->id)
                                           ->first();

        // Check if deadline has passed
        $isPastDeadline = false;
        if ($task->due_date) {
            $isPastDeadline = Carbon::parse($task->due_date)->isPast();
        }

        return view('user.task-submit', compact('task', 'existingSubmission', 'isPastDeadline'));
    }

    public function storeTaskSubmission(Request $request, Task $task)
    {
        $request->validate([
            'answer' => 'nullable|string|max:5000',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'link' => 'nullable|url|max:500',
        ]);

        // Validate that at least one field is provided
        if (!$request->filled('answer') && !$request->hasFile('file') && !$request->filled('link')) {
            return redirect()->back()
                           ->withErrors(['submission' => 'Harap isi minisnal satu: jawaban, file, atau link.'])
                           ->withInput();
        }

        // Check if user can access this task
        $canAccess = $task->assigned_to === 'all_users' ||
                    $task->assignments()->where('user_id', auth()->id)->exists();

        if (!$canAccess) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Block submissions after deadline
        if ($task->due_date && Carbon::parse($task->due_date)->isPast()) {
            return redirect()->back()
                           ->with('flash.banner', 'Waktu pengumpulan telah habis. Anda tidak dapat lagi mengumpulkan atau mengedit tugas ini.')
                           ->with('flash.bannerStyle', 'danger');
        }

        try {
            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('task-submissions', 'public');
            }

            // Check if already submitted
            $existingSubmission = TaskSubmission::where('task_id', $task->id)
                                               ->where('user_id', auth()->id)
                                               ->first();

            if ($existingSubmission) {
                // Update existing submission
                if ($existingSubmission->file_path && $filePath) {
                    Storage::disk('public')->delete($existingSubmission->file_path);
                }
                $existingSubmission->update([
                    'answer' => $request->answer,
                    'file_path' => $filePath ?: $existingSubmission->file_path,
                    'link' => $request->link,
                    'submitted_at' => now(),
                    'status' => 'pending', // Reset status to pending when resubmitted
                ]);

                return redirect()->route('user.tasks.detail', $task->id)
                               ->with('flash.banner', 'Jawaban berhasil diperbarui!')
                               ->with('flash.bannerStyle', 'success');
            } else {
                // Create new submission
                TaskSubmission::create([
                    'task_id' => $task->id,
                    'user_id' => auth()->id,
                    'answer' => $request->answer,
                    'file_path' => $filePath,
                    'link' => $request->link,
                    'submitted_at' => now(),
                ]);

                return redirect()->route('user.tasks.detail', $task->id)
                               ->with('flash.banner', 'Jawaban berhasil dikirim!')
                               ->with('flash.bannerStyle', 'success');
            }
        } catch (\Throwable $th) {
            return redirect()->back()
                           ->with('flash.banner', 'Terjadi kesalahan: ' . $th->getMessage())
                           ->with('flash.bannerStyle', 'danger');
        }
    }

    public function myAnswers()
    {
        $submissions = TaskSubmission::where('user_id', auth()->id)
                                   ->with('task')
                                   ->orderBy('submitted_at', 'desc')
                                   ->get();

        return view('user.my-answers', compact('submissions'));
    }
}
