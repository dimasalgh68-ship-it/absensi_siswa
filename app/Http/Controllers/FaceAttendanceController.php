<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\FaceRegistration;
use App\Services\FaceRecognitionService;
use App\Services\GeolocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class FaceAttendanceController extends Controller
{
    protected FaceRecognitionService $faceService;
    protected GeolocationService $geoService;

    public function __construct(
        FaceRecognitionService $faceService,
        GeolocationService $geoService
    ) {
        $this->faceService = $faceService;
        $this->geoService = $geoService;
    }

    /**
     * Show face attendance page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if user has registered face
        $hasFaceRegistration = FaceRegistration::where('user_id', $user->id)
            ->where('is_active', true)
            ->exists();

        // Get today's attendance
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', today())
            ->first();

        // Get office locations for map
        $offices = $this->geoService->getActiveOffices();

        return view('attendances.face-scan', compact(
            'hasFaceRegistration',
            'todayAttendance',
            'offices'
        ));
    }

    /**
     * Process face attendance (absen masuk/keluar).
     */
    public function store(Request $request)
    {
        // Debug: Log request data
        Log::info('Face attendance request', [
            'has_photo' => $request->hasFile('photo'),
            'photo_null' => $request->file('photo') === null,
            'all_files' => $request->allFiles(),
            'all_input' => $request->except(['photo']),
        ]);

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'type' => 'required|in:clock_in,clock_out',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Check if photo file exists
            if (!$request->hasFile('photo')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Foto tidak ditemukan. Pastikan foto berhasil diambil.',
                ], 422);
            }

            $photoFile = $request->file('photo');
            
            if (!$photoFile || !$photoFile->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'File foto tidak valid.',
                ], 422);
            }

            // Step 1: Validasi Lokasi (GPS)
            $locationValidation = $this->geoService->validateLocation(
                $request->latitude,
                $request->longitude
            );

            if (!$locationValidation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $locationValidation['message'],
                    'step' => 'location',
                ], 422);
            }

            // Step 2: Face verification (already done in browser if verified_in_browser is true)
            $similarity = $request->input('similarity', 0);
            $verifiedInBrowser = $request->input('verified_in_browser', false);

            if ($verifiedInBrowser) {
                // Validate similarity from browser
                $threshold = \App\Models\Setting::get('face_similarity_threshold', 70);
                if ($similarity < $threshold) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Wajah tidak cocok. Similarity score harus minimal ' . $threshold . '%. Skor Anda: ' . number_format($similarity, 1) . '%',
                        'similarity' => $similarity,
                        'step' => 'face',
                    ], 422);
                }
            } else {
                // Fallback: verify in PHP if not verified in browser
                $faceValidation = $this->faceService->verifyFace(
                    $user,
                    $photoFile
                );

                if (!$faceValidation['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => $faceValidation['message'],
                        'similarity' => $faceValidation['similarity'],
                        'step' => 'face',
                    ], 422);
                }

                $similarity = $faceValidation['similarity'];
            }

            // Step 3: Pencatatan Log Absensi
            $photoPath = $photoFile->store('face-verifications', 'public');

            $attendanceData = [
                'user_id' => $user->id,
                'date' => today(),
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'face_photo_path' => $photoPath,
                'face_similarity_score' => $similarity,
                'validation_method' => 'face',
            ];

            // Cek data absensi hari ini
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', today())
                ->first();

            if ($request->type === 'clock_in') {
                // Check if already clocked in today
                if ($attendance && $attendance->time_in) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah melakukan absen masuk hari ini pada ' . Carbon::parse($attendance->time_in)->format('H:i:s'),
                    ], 422);
                }

                // Validate time window for clock in
                $schedule_id = $attendance ? $attendance->shift_id : ($user->shift_id ?? null);
                
                if ($schedule_id) {
                    $schedule = \App\Models\Shift::find($schedule_id);
                    if ($schedule) {
                        $clockInEarlyMinutes = (int) \App\Models\Setting::get('clock_in_early_minutes', 60);
                        $clockInLateMinutes = (int) \App\Models\Setting::get('clock_in_late_minutes', 120);
                        $scheduleStartTime = Carbon::today()->setTimeFromTimeString($schedule->start_time);
                        $clockInEarliestTime = $scheduleStartTime->copy()->subMinutes($clockInEarlyMinutes);
                        $clockInLatestTime = $scheduleStartTime->copy()->addMinutes($clockInLateMinutes);
                        
                        // Check if trying to clock in too early
                        if (now()->lt($clockInEarliestTime)) {
                            $minutesUntil = now()->diffInMinutes($clockInEarliestTime);
                            $hoursUntil = floor($minutesUntil / 60);
                            $minsUntil = $minutesUntil % 60;
                            
                            $timeString = $hoursUntil > 0 
                                ? "{$hoursUntil} jam {$minsUntil} menit" 
                                : "{$minsUntil} menit";
                            
                            return response()->json([
                                'success' => false,
                                'message' => "Absen masuk untuk shift {$schedule->name} ({$schedule->start_time} - {$schedule->end_time}) hanya dapat dilakukan {$clockInEarlyMinutes} menit sebelum jam masuk. Silakan tunggu {$timeString} lagi.",
                            ], 422);
                        }
                        
                        // Check if trying to clock in after deadline
                        if (now()->gt($clockInLatestTime)) {
                            // Create absent record if not exists
                            if (!$attendance) {
                                Attendance::create([
                                    'user_id' => $user->id,
                                    'date' => today(),
                                    'time_in' => null,
                                    'time_out' => null,
                                    'schedule_id' => $schedule_id,
                                    'latitude' => null,
                                    'longitude' => null,
                                    'face_photo_path' => null,
                                    'face_similarity_score' => null,
                                    'validation_method' => 'system',
                                    'status' => 'absent',
                                    'note' => 'Otomatis tercatat alpha karena tidak absen dalam batas waktu yang ditentukan',
                                ]);
                                
                                Attendance::clearUserAttendanceCache($user, today());
                            }
                            
                            return response()->json([
                                'success' => false,
                                'message' => "Waktu absen masuk untuk shift {$schedule->name} ({$schedule->start_time} - {$schedule->end_time}) telah berakhir. Batas waktu absen adalah {$clockInLateMinutes} menit setelah jam masuk ({$clockInLatestTime->format('H:i')}). Anda tercatat ALPHA hari ini.",
                            ], 422);
                        }
                    }
                }

                // Determine status based on schedule (if exists)
                $status = $attendance ? $attendance->status : 'present';
                $schedule_id = $attendance ? $attendance->shift_id : ($user->shift_id ?? null);
                
                if ($schedule_id) {
                    $schedule = \App\Models\Shift::find($schedule_id);
                    if ($schedule) {
                        $lateToleranceMinutes = \App\Models\Setting::get('late_tolerance_minutes', 15);
                        $scheduleStartTime = Carbon::today()->setTimeFromTimeString($schedule->start_time);
                        $lateThreshold = $scheduleStartTime->copy()->addMinutes($lateToleranceMinutes);
                        
                        if (now()->gt($lateThreshold)) {
                            $status = 'late';
                        }
                    }
                }

                if ($attendance) {
                    $attendance->update([
                        'time_in' => now()->format('H:i:s'),
                        'status' => $status,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                        'face_photo_path' => $photoPath,
                        'face_similarity_score' => $similarity,
                        'validation_method' => 'face',
                    ]);
                } else {
                    $attendance = Attendance::create([
                        'user_id' => $user->id,
                        'date' => today(),
                        'time_in' => now()->format('H:i:s'),
                        'status' => $status,
                        'shift_id' => $schedule_id,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                        'face_photo_path' => $photoPath,
                        'face_similarity_score' => $similarity,
                        'validation_method' => 'face',
                    ]);
                }
                
                $message = $status === 'late' 
                    ? 'Absen masuk berhasil! (Terlambat)' 
                    : 'Absen masuk berhasil!';

            } else {
                // Clock out
                if ($attendance && $attendance->time_out) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah melakukan absen keluar hari ini pada ' . Carbon::parse($attendance->time_out)->format('H:i:s'),
                    ], 422);
                }

                // Check if can clock out (must have clocked in)
                if (!$attendance || !$attendance->time_in) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda belum melakukan absen masuk hari ini.',
                    ], 422);
                }

                // Check if can clock out (time check)
                $schedule_id = $attendance->shift_id ?: ($user->shift_id ?? null);
                if ($schedule_id) {
                    $schedule = \App\Models\Shift::find($schedule_id);
                    if ($schedule) {
                        $clockOutEarlyMinutes = \App\Models\Setting::get('clock_out_early_minutes', 30);
                        $scheduleEndTime = Carbon::today()->setTimeFromTimeString($schedule->end_time);
                        $allowedClockOutTime = $scheduleEndTime->copy()->subMinutes($clockOutEarlyMinutes);
                        
                        if (now()->lt($allowedClockOutTime)) {
                            $diff = now()->diff($allowedClockOutTime);
                            $timeRemaining = "";
                            if ($diff->h > 0) $timeRemaining .= $diff->h . " jam ";
                            if ($diff->i > 0) $timeRemaining .= $diff->i . " menit";
                            if ($timeRemaining === "") $timeRemaining = "beberapa detik";

                            return response()->json([
                                'success' => false,
                                'message' => "Absen keluar hanya dapat dilakukan mulai " . $allowedClockOutTime->format('H:i') . " ({$clockOutEarlyMinutes} menit sebelum jam pulang). Silakan tunggu {$timeRemaining} lagi.",
                            ], 422);
                        }
                    }
                }

                // Update data absensi
                $attendance->update([
                    'time_out' => now()->format('H:i:s'),
                    'face_photo_out_path' => $photoPath,
                    'face_similarity_score_out' => $similarity,
                    // Update lokasi terbaru
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);

                $message = 'Absen keluar berhasil!';
            }

            DB::commit();

            // Clear attendance cache
            Attendance::clearUserAttendanceCache($user, today());

            $responseData = [
                'success' => true,
                'message' => $message,
                'data' => [
                    'attendance' => $attendance,
                    'similarity' => $similarity,
                ],
            ];

            // Add location info only if office exists
            if ($locationValidation['office']) {
                $responseData['data']['location'] = [
                    'office' => $locationValidation['office']->name,
                    'distance' => $locationValidation['distance'],
                ];
            }

            return response()->json($responseData);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Face attendance error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
