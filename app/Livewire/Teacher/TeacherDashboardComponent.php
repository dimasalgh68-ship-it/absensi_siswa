<?php

namespace App\Livewire\Teacher;

use App\Livewire\Traits\AttendanceDetailTrait;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Material;
use App\Models\Task;
use App\Models\Exam;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TeacherDashboardComponent extends Component
{
    use WithPagination;
    use AttendanceDetailTrait;

    protected $paginationTheme = 'bootstrap';

    public $selectedClassId = null;

    public function mount()
    {
        $teacher = Auth::user();
        
        $taughtEducationIds = Schedule::where('teacher_id', $teacher->id)
            ->pluck('education_id')
            ->toArray();
            
        if ($teacher->education_id) {
            $taughtEducationIds[] = $teacher->education_id;
        }
        
        $taughtEducationIds = array_unique(array_filter($taughtEducationIds));
        
        if (!empty($taughtEducationIds)) {
            // Prefer homeroom class if available
            $this->selectedClassId = $teacher->education_id && in_array($teacher->education_id, $taughtEducationIds) 
                ? $teacher->education_id 
                : $taughtEducationIds[0];
        }
    }

    /**
     * Quick update status for existing attendance
     */
    public function updateStatus($attendanceId, $status)
    {
        $validStatuses = ['present', 'late', 'excused', 'sick', 'absent'];
        if (!in_array($status, $validStatuses)) {
            session()->flash('error', 'Status tidak valid.');
            return;
        }

        try {
            $attendance = Attendance::findOrFail($attendanceId);
            $attendance->update([
                'status' => $status
            ]);

            Attendance::clearUserAttendanceCache($attendance->user, $attendance->date);
            session()->flash('success', 'Status absensi siswa berhasil diperbarui.');
            $this->dispatch('attendance-updated');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $teacher = Auth::user();
        $date = \Carbon\Carbon::now();

        // Taught classes
        $taughtEducationIds = Schedule::where('teacher_id', $teacher->id)
            ->pluck('education_id')
            ->toArray();
            
        if ($teacher->education_id) {
            $taughtEducationIds[] = $teacher->education_id;
        }
        $taughtEducationIds = array_unique(array_filter($taughtEducationIds));
        $myClasses = \App\Models\Education::whereIn('id', $taughtEducationIds)->get();

        $mySubjects = Schedule::where('teacher_id', $teacher->id)
            ->with('subject')
            ->get()
            ->pluck('subject.name')
            ->unique()
            ->filter();

        $selectedClassName = $myClasses->where('id', $this->selectedClassId)->first()?->name ?? 'Belum Ditentukan';

        // 1. Get students of the selected class
        $studentsQuery = User::where('group', 'student');
        if ($this->selectedClassId) {
            $studentsQuery->where('education_id', $this->selectedClassId);
        } else {
            // no class selected/taught, don't show any students
            $studentsQuery->whereNull('id');
        }
        
        $employeesCount = $studentsQuery->count();

        // 2. Get attendance for today of these students
        $studentIds = $studentsQuery->pluck('id')->toArray();
        $attendances = Attendance::where('date', date('Y-m-d'))
            ->whereIn('user_id', $studentIds)
            ->get();

        $presentCount = $attendances->where('status', 'present')->count();
        $lateCount = $attendances->where('status', 'late')->count();
        $excusedCount = $attendances->where('status', 'excused')->count();
        $sickCount = $attendances->where('status', 'sick')->count();
        $absentCount = max(0, $employeesCount - ($presentCount + $lateCount + $excusedCount + $sickCount));

        // 3. Paginated list of students in their class with today's attendance
        $employees = User::where('group', 'student');
        if ($this->selectedClassId) {
            $employees->where('education_id', $this->selectedClassId);
        } else {
            $employees->whereNull('id');
        }
        $employees = $employees->paginate(15)->through(function (User $user) use ($attendances) {
            return $user->setAttribute(
                'attendance',
                $attendances->where('user_id', $user->id)->first()
            );
        });

        // 4. Teaching schedules for THIS teacher today
        $todayDayName = $date->translatedFormat('l'); // e.g. "Senin"
        $todaySchedules = Schedule::with(['subject', 'education', 'room'])
            ->where('teacher_id', $teacher->id)
            ->where('day', $todayDayName)
            ->orderBy('start_time', 'asc')
            ->get();

        // 5. Materials, tasks and exams uploaded by THIS teacher
        $materiCount = Material::where('teacher_id', $teacher->id)->count();
        $tasksCount = Task::where('created_by', $teacher->id)->count();
        $examsCount = Exam::where('teacher_id', $teacher->id)->count();

        // 6. Recent tasks waiting for submission or created recently by THIS teacher
        $recentTasks = Task::where('created_by', $teacher->id)
            ->latest()
            ->take(3)
            ->get();

        // 7. Active Shift and attendance countdown
        $shift = Shift::first();
        $clockInDeadline = null;
        $clockOutTime = null;
        $clockInOpenTime = null;
        
        if ($shift) {
            $clockInEarlyMinutes = (int) Setting::get('clock_in_early_minutes', 60);
            $clockInLateMinutes = (int) Setting::get('clock_in_late_minutes', 120);
            $scheduleStartTime = \Carbon\Carbon::today()->setTimeFromTimeString($shift->start_time);
            
            $clockInOpenTime = $scheduleStartTime->copy()->subMinutes($clockInEarlyMinutes);
            $clockInDeadline = $scheduleStartTime->copy()->addMinutes($clockInLateMinutes);
            $clockOutTime = \Carbon\Carbon::today()->setTimeFromTimeString($shift->end_time);
        }

        return view('livewire.teacher.teacher-dashboard-component', [
            'employees' => $employees,
            'employeesCount' => $employeesCount,
            'presentCount' => $presentCount,
            'lateCount' => $lateCount,
            'excusedCount' => $excusedCount,
            'sickCount' => $sickCount,
            'absentCount' => $absentCount,
            'clockInOpenTime' => $clockInOpenTime,
            'clockInDeadline' => $clockInDeadline,
            'clockOutTime' => $clockOutTime,
            'shift' => $shift,
            'materiCount' => $materiCount,
            'tasksCount' => $tasksCount,
            'examsCount' => $examsCount,
            'todaySchedules' => $todaySchedules,
            'recentTasks' => $recentTasks,
            'todayDayName' => $todayDayName,
            'teacherClass' => $teacher->education?->name ?? 'Belum Ditentukan',
            'myClasses' => $myClasses,
            'mySubjects' => $mySubjects,
            'selectedClassName' => $selectedClassName,
        ]);
    }
}
