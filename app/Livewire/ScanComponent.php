<?php

namespace App\Livewire;

use App\ExtendedCarbon;
use App\Models\Attendance;
use App\Models\FaceRegistration;
use App\Models\Shift;
use App\Services\FaceRecognitionService;
use App\Services\GeolocationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Carbon;

class ScanComponent extends Component
{
    use WithFileUploads;

    public ?Attendance $attendance = null;
    public $schedule_id = null;
    public $schedules = null;
    public ?array $currentLiveCoords = null;
    public string $successMsg = '';
    public bool $isAbsence = false;
    public $photo;
    public bool $hasFaceRegistration = false;
    public bool $canClockOut = false;
    public ?string $clockOutAvailableAt = null;
    public ?int $minutesUntilClockOut = null;

    protected FaceRecognitionService $faceService;
    protected GeolocationService $geoService;

    public function boot(
        FaceRecognitionService $faceService,
        GeolocationService $geoService
    ) {
        $this->faceService = $faceService;
        $this->geoService = $geoService;
    }

    public function scanFace($photoData, $latitude, $longitude)
    {
        try {
            if (!$this->hasFaceRegistration) {
                return [
                    'success' => false,
                    'message' => 'Wajah belum terdaftar. Silakan daftar terlebih dahulu.'
                ];
            } 

            if (is_null($latitude) || is_null($longitude)) {
                return [
                    'success' => false,
                    'message' => 'Lokasi tidak valid. Pastikan GPS aktif.'
                ];
            }

            if (is_null($this->schedule_id)) {
                return [
                    'success' => false,
                    'message' => 'Jadwal tidak valid.'
                ];
            }

            // Step 0: Validasi Waktu Absensi sesuai Shift
            $timeValidation = $this->validateAttendanceTime($this->schedule_id);
            if (!$timeValidation['valid']) {
                return [
                    'success' => false,
                    'message' => $timeValidation['message']
                ];
            }

            // Step 1: Validasi Lokasi (GPS)
            $locationValidation = $this->geoService->validateLocation($latitude, $longitude);
 
            if (!$locationValidation['valid']) {
                return [
                    'success' => false,
                    'message' => $locationValidation['message']
                ];
            }

            // Step 2: Validasi Wajah (Face Recognition)
            // Convert base64 to file
            $photoFile = $this->base64ToFile($photoData);
            
            $faceValidation = $this->faceService->verifyFace(Auth::user(), $photoFile);

            if (!$faceValidation['success']) {
                return [
                    'success' => false,
                    'message' => $faceValidation['message'],
                    'similarity' => $faceValidation['similarity']
                ];
            }

            // Step 3: Pencatatan Log Absensi
            $existingAttendance = Attendance::where('user_id', Auth::user()->id)
                ->whereDate('date', today())
                ->first();

            if (!$existingAttendance) {
                // Clock In
                $attendance = $this->createAttendance($latitude, $longitude, $faceValidation);
                $this->successMsg = __('Absen Masuk Berhasil!');
            } else {
                // Clock Out
                if ($existingAttendance->time_out) {
                    return [
                        'success' => false,
                        'message' => 'Anda sudah melakukan absen keluar hari ini pada ' . $existingAttendance->time_out
                    ];
                }

                if (!$existingAttendance->time_in) {
                    return [
                        'success' => false,
                        'message' => 'Anda belum melakukan absen masuk hari ini.'
                    ];
                }

                // Check if can clock out (30 minutes before shift end)
                $canClockOutCheck = $this->checkCanClockOut($existingAttendance);
                if (!$canClockOutCheck['can_clock_out']) {
                    return [
                        'success' => false,
                        'message' => $canClockOutCheck['message']
                    ];
                }

                $attendance = $existingAttendance;
                $attendance->update([
                    'time_out' => now()->format('H:i:s'),
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'face_photo_out_path' => $faceValidation['photo_path'],
                    'face_similarity_score_out' => $faceValidation['similarity'],
                ]);
                $this->successMsg = __('Absen Keluar Berhasil!');
            }

            if ($attendance) {
                $this->setAttendance($attendance->fresh());
                Attendance::clearUserAttendanceCache(Auth::user(), Carbon::parse($attendance->date));
                
                return [
                    'success' => true,
                    'message' => $this->successMsg,
                    'similarity' => $faceValidation['similarity'],
                    'office' => $locationValidation['office']->name,
                    'distance' => $locationValidation['distance']
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menyimpan absensi'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    protected function checkCanClockOut(Attendance $attendance): array
    {
        if (!$attendance->schedule_id) {
            // No schedule, allow clock out anytime
            return [
                'can_clock_out' => true,
                'message' => ''
            ];
        }

        $schedule = Shift::find($attendance->schedule_id);
        if (!$schedule) {
            return [
                'can_clock_out' => true,
                'message' => ''
            ];
        }

        // Get clock out early minutes from settings (default 30)
        $clockOutEarlyMinutes = \App\Models\Setting::get('clock_out_early_minutes', 30);

        // Calculate X minutes before schedule end time
        $scheduleEndTime = Carbon::today()->setTimeFromTimeString($schedule->end_time);
        $allowedClockOutTime = $scheduleEndTime->copy()->subMinutes($clockOutEarlyMinutes);
        $now = Carbon::now();

        if ($now->lt($allowedClockOutTime)) {
            $minutesRemaining = $now->diffInMinutes($allowedClockOutTime);
            $hoursRemaining = floor($minutesRemaining / 60);
            $minsRemaining = $minutesRemaining % 60;
            
            $timeString = '';
            if ($hoursRemaining > 0) {
                $timeString = $hoursRemaining . ' jam ' . $minsRemaining . ' menit';
            } else {
                $timeString = $minsRemaining . ' menit';
            }

            return [
                'can_clock_out' => false,
                'message' => "Absen keluar hanya dapat dilakukan {$clockOutEarlyMinutes} menit sebelum jam pulang ({$schedule->end_time}). Silakan tunggu {$timeString} lagi.",
                'minutes_remaining' => $minutesRemaining,
                'allowed_at' => $allowedClockOutTime->format('H:i')
            ];
        }

        return [
            'can_clock_out' => true,
            'message' => ''
        ];
    }

    protected function validateAttendanceTime($scheduleId): array
    {
        $schedule = Shift::find($scheduleId);
        if (!$schedule) {
            return [
                'valid' => false,
                'message' => 'Jadwal tidak ditemukan.'
            ];
        }

        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');
        
        // Get settings
        $clockInEarlyMinutes = (int) \App\Models\Setting::get('clock_in_early_minutes', 60); // 60 menit sebelum shift
        $clockInLateMinutes = (int) \App\Models\Setting::get('clock_in_late_minutes', 120); // 120 menit setelah shift mulai
        
        // Parse shift times
        $shiftStart = Carbon::today()->setTimeFromTimeString($schedule->start_time);
        $shiftEnd = Carbon::today()->setTimeFromTimeString($schedule->end_time);
        
        // Calculate valid time windows
        $clockInEarliestTime = $shiftStart->copy()->subMinutes($clockInEarlyMinutes);
        $clockInLatestTime = $shiftStart->copy()->addMinutes($clockInLateMinutes);
        
        // Check if there's already an attendance today
        $existingAttendance = Attendance::where('user_id', Auth::user()->id)
            ->whereDate('date', today())
            ->first();

        if (!$existingAttendance) {
            // Validasi Clock In
            if ($now->lt($clockInEarliestTime)) {
                $minutesUntil = $now->diffInMinutes($clockInEarliestTime);
                $hoursUntil = floor($minutesUntil / 60);
                $minsUntil = $minutesUntil % 60;
                
                $timeString = $hoursUntil > 0 
                    ? "{$hoursUntil} jam {$minsUntil} menit" 
                    : "{$minsUntil} menit";
                
                return [
                    'valid' => false,
                    'message' => "Absen masuk untuk shift {$schedule->name} ({$schedule->start_time} - {$schedule->end_time}) hanya dapat dilakukan {$clockInEarlyMinutes} menit sebelum jam masuk. Silakan tunggu {$timeString} lagi."
                ];
            }
            
            if ($now->gt($clockInLatestTime)) {
                // Waktu absen sudah lewat - Catat sebagai ALPHA
                $this->createAbsentAttendance($schedule);
                
                return [
                    'valid' => false,
                    'message' => "Waktu absen masuk untuk shift {$schedule->name} ({$schedule->start_time} - {$schedule->end_time}) telah berakhir. Batas waktu absen adalah {$clockInLateMinutes} menit setelah jam masuk ({$clockInLatestTime->format('H:i')}). Anda tercatat ALPHA hari ini."
                ];
            }
            
            return [
                'valid' => true,
                'message' => ''
            ];
        } else {
            // Validasi Clock Out
            if (!$existingAttendance->time_in) {
                return [
                    'valid' => false,
                    'message' => 'Anda belum melakukan absen masuk hari ini.'
                ];
            }
            
            if ($existingAttendance->time_out) {
                return [
                    'valid' => false,
                    'message' => 'Anda sudah melakukan absen keluar hari ini pada ' . $existingAttendance->time_out
                ];
            }
            
            // Validasi waktu clock out (menggunakan checkCanClockOut yang sudah ada)
            $canClockOutCheck = $this->checkCanClockOut($existingAttendance);
            if (!$canClockOutCheck['can_clock_out']) {
                return [
                    'valid' => false,
                    'message' => $canClockOutCheck['message']
                ];
            }
            
            return [
                'valid' => true,
                'message' => ''
            ];
        }
    }

    /**
     * Create absent (alpha) attendance record when student tries to clock in after deadline
     */
    protected function createAbsentAttendance($schedule)
    {
        $existingAttendance = Attendance::where('user_id', Auth::user()->id)
            ->whereDate('date', today())
            ->first();

        // Only create if not already exists
        if (!$existingAttendance) {
            Attendance::create([
                'user_id' => Auth::user()->id,
                'date' => today(),
                'time_in' => null,
                'time_out' => null,
                'schedule_id' => $schedule->id,
                'latitude' => null,
                'longitude' => null,
                'face_photo_path' => null,
                'face_similarity_score' => null,
                'validation_method' => 'system',
                'status' => 'absent', // ALPHA
                'note' => 'Otomatis tercatat alpha karena tidak absen dalam batas waktu yang ditentukan',
                'attachment' => null,
            ]);

            // Clear cache
            Attendance::clearUserAttendanceCache(Auth::user(), today());
        }
    }

    protected function base64ToFile($base64Data)
    {
        // Remove data:image/...;base64, prefix
        $base64Data = preg_replace('/^data:image\/\w+;base64,/', '', $base64Data);
        $imageData = base64_decode($base64Data);
        
        $tmpFile = tmpfile();
        $tmpPath = stream_get_meta_data($tmpFile)['uri'];
        fwrite($tmpFile, $imageData);
        
        return new \Illuminate\Http\UploadedFile(
            $tmpPath,
            'face.jpg',
            'image/jpeg',
            null,
            true
        );
    }

    protected function createAttendance($latitude, $longitude, $faceValidation)
    {
        $now = Carbon::now();
        $date = $now->format('Y-m-d');
        $timeIn = $now->format('H:i:s');
        
        /** @var Shift */
        $schedule = Shift::find($this->schedule_id);
        
        // Get late tolerance from settings (default 15 minutes)
        $lateToleranceMinutes = \App\Models\Setting::get('late_tolerance_minutes', 15);
        
        // Calculate if late: clock in time > (schedule start time + tolerance)
        $scheduleStartTime = Carbon::today()->setTimeFromTimeString($schedule->start_time);
        $lateThreshold = $scheduleStartTime->copy()->addMinutes($lateToleranceMinutes);
        
        $status = $now->gt($lateThreshold) ? 'late' : 'present';
        
        return Attendance::create([
            'user_id' => Auth::user()->id,
            'date' => $date,
            'time_in' => $timeIn,
            'time_out' => null,
            'schedule_id' => $schedule->id,
            'latitude' => doubleval($latitude),
            'longitude' => doubleval($longitude),
            'face_photo_path' => $faceValidation['photo_path'],
            'face_similarity_score' => $faceValidation['similarity'],
            'validation_method' => 'face',
            'status' => $status,
            'note' => null,
            'attachment' => null,
        ]);
    }

    protected function setAttendance(Attendance $attendance)
    {
        $this->attendance = $attendance;
        $this->schedule_id = $attendance->schedule_id;
        $this->isAbsence = $attendance->status !== 'present' && $attendance->status !== 'late';
        
        // Update clock out availability
        $this->updateClockOutAvailability();
    }

    protected function updateClockOutAvailability()
    {
        if (!$this->attendance || !$this->attendance->time_in || $this->attendance->time_out) {
            $this->canClockOut = false;
            $this->clockOutAvailableAt = null;
            $this->minutesUntilClockOut = null;
            return;
        }

        $checkResult = $this->checkCanClockOut($this->attendance);
        $this->canClockOut = $checkResult['can_clock_out'];
        
        if (!$this->canClockOut && isset($checkResult['allowed_at'])) {
            $this->clockOutAvailableAt = $checkResult['allowed_at'];
            $this->minutesUntilClockOut = $checkResult['minutes_remaining'];
        } else {
            $this->clockOutAvailableAt = null;
            $this->minutesUntilClockOut = null;
        }
    }

    public function getAttendance()
    {
        if (is_null($this->attendance)) {
            return null;
        }
        return [
            'time_in' => $this->attendance?->time_in,
            'time_out' => $this->attendance?->time_out,
        ];
    }

    public function mount()
    {
        $this->schedules = Shift::all();

        // Check if user has face registration
        $this->hasFaceRegistration = FaceRegistration::where('user_id', Auth::user()->id)
            ->where('is_active', true)
            ->exists();

        /** @var Attendance */
        $attendance = Attendance::where('user_id', Auth::user()->id)
            ->where('date', date('Y-m-d'))->first();
            
        if ($attendance) {
            $this->setAttendance($attendance);
        } else {
            // get closest schedule from current time
            $closest = ExtendedCarbon::now()
                ->closestFromDateArray($this->schedules->pluck('start_time')->toArray());

            $schedule = $this->schedules
                ->where(fn (Shift $schedule) => $schedule->start_time == $closest->format('H:i:s'))
                ->first();

            if ($schedule) {
                $this->schedule_id = $schedule->id;
            } else {
                // Fallback to first schedule if no closest match
                $this->schedule_id = $this->schedules->first()?->id;
            }
        }
    }

    public function render()
    {
        return view('livewire.scan-face');
    }
}
