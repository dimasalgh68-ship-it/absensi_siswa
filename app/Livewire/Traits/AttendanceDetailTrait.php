<?php

namespace App\Livewire\Traits;

use App\Models\Attendance;

trait AttendanceDetailTrait
{
    public bool $showDetail = false;
    public $currentAttendance = [];

    public function show($attendanceId)
    {
        /** @var Attendance */
        $attendance = Attendance::find($attendanceId);
        if ($attendance) {
            $this->showDetail = true;
            $this->currentAttendance = $attendance->getAttributes();
            $this->currentAttendance['name'] = $attendance->user->name;
            $this->currentAttendance['nisn'] = $attendance->user->nisn;
            $this->currentAttendance['address'] = $attendance->user->address;
            
            // Face recognition data (Absen Masuk)
            if ($attendance->face_photo_path) {
                $this->currentAttendance['face_photo_url'] = \Storage::url($attendance->face_photo_path);
            }
            if ($attendance->face_similarity_score) {
                $this->currentAttendance['face_similarity_score'] = $attendance->face_similarity_score;
            }
            
            // Face recognition data (Absen Keluar)
            if ($attendance->face_photo_out_path) {
                $this->currentAttendance['face_photo_out_url'] = \Storage::url($attendance->face_photo_out_path);
            }
            if ($attendance->face_similarity_score_out) {
                $this->currentAttendance['face_similarity_score_out'] = $attendance->face_similarity_score_out;
            }
            
            if ($attendance->validation_method) {
                $this->currentAttendance['validation_method'] = $attendance->validation_method;
            }
            
            if ($attendance->attachment) {
                $this->currentAttendance['attachment'] = $attendance->attachment_url;
            }
            if ($attendance->shift_id) {
                $this->currentAttendance['shift'] = $attendance->shift;
            }
        }
    }
}
