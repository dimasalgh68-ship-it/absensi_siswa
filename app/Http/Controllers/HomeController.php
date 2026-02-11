<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Attendance;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();

        // Check attendance today
        $attendanceToday = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        // Get recent history
        $history = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        // Calculate attendance deadline based on current time and status
        $clockInDeadline = null;
        $clockOutTime = null;
        $nextDeadline = null;
        $deadlineType = 'clock_in'; // clock_in, clock_out, next_day, waiting
        $canClockIn = false;
        
        // Get shift from attendance or use first available shift
        $shift = null;
        if ($attendanceToday && $attendanceToday->shift_id) {
            $shift = \App\Models\Shift::find($attendanceToday->shift_id);
        } else {
            // Get first shift as default
            $shift = \App\Models\Shift::first();
        }
        
        if ($shift) {
            $clockInEarlyMinutes = (int) \App\Models\Setting::get('clock_in_early_minutes', 60);
            $clockInLateMinutes = (int) \App\Models\Setting::get('clock_in_late_minutes', 120);
            $scheduleStartTime = Carbon::today()->setTimeFromTimeString($shift->start_time);
            
            // Calculate when clock in opens (1 hour before shift)
            $clockInOpenTime = $scheduleStartTime->copy()->subMinutes($clockInEarlyMinutes);
            
            // Calculate clock in deadline
            $clockInDeadline = $scheduleStartTime->copy()->addMinutes($clockInLateMinutes);
            
            // Clock out time (end of shift)
            $clockOutTime = Carbon::today()->setTimeFromTimeString($shift->end_time);
            
            // Determine which deadline to show based on current status
            if (!$attendanceToday) {
                // Not attended yet
                if ($now->lt($clockInOpenTime)) {
                    // Too early, show countdown to when clock in opens
                    $nextDeadline = $clockInOpenTime;
                    $deadlineType = 'waiting';
                    $canClockIn = false;
                } elseif ($now->gte($clockInOpenTime) && $now->lt($clockInDeadline)) {
                    // Within clock in window
                    $nextDeadline = $clockInDeadline;
                    $deadlineType = 'clock_in';
                    $canClockIn = true;
                } else {
                    // Missed today's deadline, show tomorrow's open time
                    $nextDeadline = $clockInOpenTime->copy()->addDay();
                    $deadlineType = 'next_day';
                    $canClockIn = false;
                }
            } elseif ($attendanceToday && !$attendanceToday->time_out) {
                // Clocked in, waiting for clock out
                if ($now->lt($clockOutTime)) {
                    $nextDeadline = $clockOutTime;
                    $deadlineType = 'clock_out';
                    $canClockIn = true;
                } else {
                    // Passed clock out time, show tomorrow's open time
                    $nextDeadline = $clockInOpenTime->copy()->addDay();
                    $deadlineType = 'next_day';
                    $canClockIn = false;
                }
            } else {
                // Already completed today, show tomorrow's open time
                $nextDeadline = $clockInOpenTime->copy()->addDay();
                $deadlineType = 'next_day';
                $canClockIn = false;
            }
        }

        return view('home', compact('attendanceToday', 'history', 'clockInDeadline', 'clockOutTime', 'nextDeadline', 'deadlineType', 'canClockIn'));
    }
}
