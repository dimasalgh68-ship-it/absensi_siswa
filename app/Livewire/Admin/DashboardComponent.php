<?php

namespace App\Livewire\Admin;

use App\Livewire\Traits\AttendanceDetailTrait;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class DashboardComponent extends Component
{
    use AttendanceDetailTrait;

    /**
     * Quick update status for existing attendance
     */
    public function updateStatus($attendanceId, $status)
    {
        // Validate status
        $validStatuses = ['present', 'late', 'excused', 'sick', 'absent'];
        if (!in_array($status, $validStatuses)) {
            session()->flash('error', 'Status tidak valid.');
            return;
        }

        try {
            $attendance = Attendance::findOrFail($attendanceId);
            
            // Update status
            $attendance->update([
                'status' => $status
            ]);

            // Clear cache
            Attendance::clearUserAttendanceCache($attendance->user, $attendance->date);

            session()->flash('success', 'Status absensi berhasil diubah.');
            
            // Refresh component
            $this->dispatch('attendance-updated');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    public function render()
    {
        /** @var Collection<Attendance>  */
        $attendances = Attendance::where('date', date('Y-m-d'))
            ->whereHas('user', fn ($q) => $q->where('group', 'student'))
            ->get();

        /** @var Collection<User>  */
        $employees = User::where('group', 'student')
            ->paginate(20)
            ->through(function (User $user) use ($attendances) {
                return $user->setAttribute(
                    'attendance',
                    $attendances
                        ->where(fn (Attendance $attendance) => $attendance->user_id === $user->id)
                        ->first(),
                );
            });

        $employeesCount = User::where('group', 'student')->count();
        $presentCount = $attendances->where(fn ($attendance) => $attendance->status === 'present')->count();
        $lateCount = $attendances->where(fn ($attendance) => $attendance->status === 'late')->count();
        $excusedCount = $attendances->where(fn ($attendance) => $attendance->status === 'excused')->count();
        $sickCount = $attendances->where(fn ($attendance) => $attendance->status === 'sick')->count();
        $absentCount = max(0, $employeesCount - ($presentCount + $lateCount + $excusedCount + $sickCount));

        // Calculate countdown for first shift
        $shift = \App\Models\Shift::first();
        $clockInDeadline = null;
        $clockOutTime = null;
        $clockInOpenTime = null;
        
        if ($shift) {
            $clockInEarlyMinutes = (int) \App\Models\Setting::get('clock_in_early_minutes', 60);
            $clockInLateMinutes = (int) \App\Models\Setting::get('clock_in_late_minutes', 120);
            $scheduleStartTime = \Carbon\Carbon::today()->setTimeFromTimeString($shift->start_time);
            
            $clockInOpenTime = $scheduleStartTime->copy()->subMinutes($clockInEarlyMinutes);
            $clockInDeadline = $scheduleStartTime->copy()->addMinutes($clockInLateMinutes);
            $clockOutTime = \Carbon\Carbon::today()->setTimeFromTimeString($shift->end_time);
        }

        return view('livewire.admin.dashboard', [
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
        ]);
    }
}
