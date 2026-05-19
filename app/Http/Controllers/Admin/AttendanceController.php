<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attendance;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.attendances.index');
    }

    public function report(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date_format:Y-m-d',
            'month' => 'nullable|date_format:Y-m',
            'week' => 'nullable',
            'division' => 'nullable|exists:divisions,id',
            'job_title' => 'nullable|exists:job_titles,id',
        ]);

        if (!$request->date && !$request->month && !$request->week) {
            return redirect()->back();
        }

        $carbon = new Carbon;
        $startDate = null;
        $endDate = null;
        $dates = [];

        if ($request->date) {
            $startDate = $carbon->parse($request->date)->startOfDay();
            $endDate = $carbon->parse($request->date)->endOfDay();
            $dates = [$carbon->parse($request->date)->settings(['formatFunction' => 'translatedFormat'])];
        } else if ($request->week) {
            $startDate = $carbon->parse($request->week)->startOfWeek();
            $endDate = $carbon->parse($request->week)->endOfWeek();
            $dates = $startDate->range($endDate)->toArray();
        } else if ($request->month) {
            $startDate = $carbon->parse($request->month)->startOfMonth();
            $endDate = $carbon->parse($request->month)->endOfMonth();
            $dates = $startDate->range($endDate)->toArray();
        }

        // Eager load attendances for the date range
        $employees = User::whereIn('group', ['user', 'student'])
            ->when($request->division, fn (Builder $q) => $q->where('division_id', $request->division))
            ->when($request->jobTitle, fn (Builder $q) => $q->where('job_title_id', $request->jobTitle))
            ->with(['attendances' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            }])
            ->get()
            ->map(function ($user) use ($request, $startDate, $endDate) {
                // Transform attendances to match expected format
                $attendances = $user->attendances->map(function ($v) {
                    $v->setAttribute('coordinates', $v->lat_lng);
                    $v->setAttribute('lat', $v->latitude);
                    $v->setAttribute('lng', $v->longitude);
                    if ($v->attachment) {
                        $v->setAttribute('attachment', $v->attachment_url);
                    }
                    if ($v->shift) {
                        $v->setAttribute('shift', $v->shift->name);
                    }
                    // Add face recognition data
                    if ($v->face_photo_path) {
                        $v->setAttribute('face_photo_url', \Storage::url($v->face_photo_path));
                    }
                    if ($v->face_photo_out_path) {
                        $v->setAttribute('face_photo_out_url', \Storage::url($v->face_photo_out_path));
                    }
                    return $v->getAttributes();
                });

                // Filter for specific date if needed (though query handles it)
                if ($request->date) {
                    $user->attendances = $attendances->toArray();
                } else {
                    // For week/month, we might need just specific fields as per original code
                    // Original code for week/month returned ['id', 'status', 'date']
                    $user->attendances = $attendances->map(function ($item) {
                        return [
                            'id' => $item['id'],
                            'status' => $item['status'],
                            'date' => $item['date'],
                        ];
                    })->toArray();
                }
                
                return $user;
            });

        $pdf = Pdf::loadView('admin.attendances.report', [
            'employees' => $employees,
            'dates' => $dates,
            'date' => $request->date,
            'month' => $request->month,
            'week' => $request->week,
            'division' => $request->division,
            'jobTitle' => $request->jobTitle,
            'start' => $request->date ? null : $startDate,
            'end' => $request->date ? null : $endDate
        ])->setPaper($request->month ? 'a3' : 'a4', $request->date ? 'portrait' : 'landscape');
        
        return $pdf->stream();
    }

    public function absentReport(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date_format:Y-m-d',
            'month' => 'nullable|date_format:Y-m',
            'week' => 'nullable',
            'division' => 'nullable|exists:divisions,id',
            'job_title' => 'nullable|exists:job_titles,id',
        ]);

        if (!$request->date && !$request->month && !$request->week) {
            return redirect()->back()->with('error', 'Pilih tanggal, minggu, atau bulan');
        }

        $carbon = new Carbon;
        $startDate = null;
        $endDate = null;
        $dates = [];

        if ($request->date) {
            $startDate = $carbon->parse($request->date)->startOfDay();
            $endDate = $carbon->parse($request->date)->endOfDay();
            $dates = [$carbon->parse($request->date)->settings(['formatFunction' => 'translatedFormat'])];
        } else if ($request->week) {
            $startDate = $carbon->parse($request->week)->startOfWeek();
            $endDate = $carbon->parse($request->week)->endOfWeek();
            $dates = $startDate->range($endDate)->toArray();
        } else if ($request->month) {
            $startDate = $carbon->parse($request->month)->startOfMonth();
            $endDate = $carbon->parse($request->month)->endOfMonth();
            $dates = $startDate->range($endDate)->toArray();
        }

        // Get all students
        $allStudents = User::whereIn('group', ['user', 'student'])
            ->when($request->division, fn (Builder $q) => $q->where('division_id', $request->division))
            ->when($request->jobTitle, fn (Builder $q) => $q->where('job_title_id', $request->jobTitle))
            ->with(['division', 'jobTitle', 'education'])
            ->get();

        // Get students who have attendance in the date range
        $studentsWithAttendance = Attendance::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->whereHas('user', function ($query) use ($request) {
                $query->whereIn('group', ['user', 'student'])
                    ->when($request->division, fn (Builder $q) => $q->where('division_id', $request->division))
                    ->when($request->jobTitle, fn (Builder $q) => $q->where('job_title_id', $request->jobTitle));
            })
            ->pluck('user_id')
            ->unique();

        // Filter students who don't have attendance (absent students)
        $absentStudents = $allStudents->filter(function ($student) use ($studentsWithAttendance) {
            return !$studentsWithAttendance->contains($student->id);
        });

        // Calculate total days in range
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $pdf = Pdf::loadView('admin.attendances.absent-report', [
            'students' => $absentStudents,
            'dates' => $dates,
            'date' => $request->date,
            'month' => $request->month,
            'week' => $request->week,
            'division' => $request->division,
            'jobTitle' => $request->jobTitle,
            'start' => $startDate,
            'end' => $endDate,
            'totalDays' => $totalDays,
            'totalStudents' => $allStudents->count(),
            'absentCount' => $absentStudents->count(),
        ])->setPaper('a4', 'portrait');
        
        return $pdf->stream('laporan-siswa-tidak-hadir-' . $startDate->format('Y-m-d') . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }
}
