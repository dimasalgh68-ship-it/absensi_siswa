<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Shift;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class AttendancesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    public function __construct(public bool $save = true)
    {
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        [$lat, $lng] = [null, null];
        
        // CRITICAL BUG FIX #4: Validate coordinates before using them
        if (isset($row['coordinates'])) {
            [$lat, $lng] = explode(',', $row['coordinates']);
            
            // Validate latitude and longitude ranges
            $lat = trim($lat);
            $lng = trim($lng);
            
            $lat_double = doubleval($lat);
            $lng_double = doubleval($lng);
            
            // Check if coordinates are within valid ranges
            if ($lat_double < -90 || $lat_double > 90 || $lng_double < -180 || $lng_double > 180) {
                // Invalid coordinates, set to null
                $lat = null;
                $lng = null;
            } else {
                $lat = $lat_double;
                $lng = $lng_double;
            }
        }
        
        // CRITICAL BUG FIX #5: Prevent SQL Injection - validate shift name before query
        $shift_id = null;
        if (isset($row['shift']) && !empty($row['shift'])) {
            // Sanitize shift name - only allow alphanumeric, spaces, and hyphens
            $shiftName = trim($row['shift']);
            
            // Validate shift name format (prevent SQL injection)
            if (preg_match('/^[a-zA-Z0-9\s\-]+$/', $shiftName)) {
                // Use parameterized query (Laravel's where() uses prepared statements)
                $shift = Shift::where('name', $shiftName)->first();
                $shift_id = $shift?->id ?? ($row['shift_id'] ?? null);
            } else {
                // Invalid shift name format, use shift_id if provided
                $shift_id = $row['shift_id'] ?? null;
            }
        } else {
            $shift_id = $row['shift_id'] ?? null;
        }

        $attendance = (new Attendance)->forceFill([
            'user_id' => $row['user_id'],
            'barcode_id' => $row['barcode_id'],
            'date' => $row['date'],
            'time_in' => $row['time_in'],
            'time_out' => $row['time_out'],
            'shift_id' => $shift_id,
            'latitude' => $lat,
            'longitude' => $lng,
            'status' => $this->getStatus($row['status']) ?? $row['raw_status'],
            'note' => $row['note'],
            'attachment' => $row['attachment'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
        ]);
        if ($this->save) {
            $attendance->save();
        }
        return $attendance;
    }

    private function getStatus($status)
    {
        switch (Str::lower($status)) {
            case 'hadir':
                return 'present';
            case 'terlambat':
                return 'late';
            case 'izin':
                return 'excused';
            case 'sakit':
                return 'sick';
            case 'tidak hadir':
                return 'absent';
            default:
                return null;
        }
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'date' => 'required',
            'status' => 'required',
            // 'shift' => 'nullable|exists:shifts,name',
            // 'barcode_id' => 'nullable|exists:barcodes,id',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
    }
}
