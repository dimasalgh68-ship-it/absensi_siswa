<?php

namespace App\Imports;

use App\Models\Division;
use App\Models\Education;
use App\Models\JobTitle;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
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
        // CRITICAL BUG FIX #6: Validate and prevent auto-creation of related records
        // Only use existing records, don't auto-create to prevent data pollution
        
        $division_id = null;
        if (isset($row['division']) && !empty($row['division'])) {
            $division = Division::where('name', trim($row['division']))->first();
            if (!$division) {
                // Log warning instead of auto-creating
                \Log::warning('Division not found during import: ' . $row['division']);
                // Skip this row or use null - don't auto-create
                $division_id = null;
            } else {
                $division_id = $division->id;
            }
        }
        
        $job_title_id = null;
        if (isset($row['job_title']) && !empty($row['job_title'])) {
            $jobTitle = JobTitle::where('name', trim($row['job_title']))->first();
            if (!$jobTitle) {
                // Log warning instead of auto-creating
                \Log::warning('JobTitle not found during import: ' . $row['job_title']);
                $job_title_id = null;
            } else {
                $job_title_id = $jobTitle->id;
            }
        }
        
        $education_id = null;
        if (isset($row['education']) && !empty($row['education'])) {
            $education = Education::where('name', trim($row['education']))->first();
            if (!$education) {
                // Log warning instead of auto-creating
                \Log::warning('Education not found during import: ' . $row['education']);
                $education_id = null;
            } else {
                $education_id = $education->id;
            }
        }
        
        // Validate password strength
        $password = $row['password'] ?? '';
        if (strlen($password) < 8) {
            \Log::warning('Password too weak for user: ' . ($row['email'] ?? 'unknown'));
            // You might want to skip this row or use a default password
            // For now, we'll allow it but log the warning
        }
        
        $user = (new User)->forceFill([
            'id' => isset($row['id']) ? $row['id'] : null,
            'nisn' => $row['nisn'],
            'name' => $row['name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'gender' => $row['gender'],
            'birth_date' => $row['birth_date'],
            'birth_place' => $row['birth_place'],
            'address' => $row['address'],
            'city' => $row['city'],
            'education_id' => $education_id,
            'division_id' => $division_id,
            'job_title_id' => $job_title_id,
            'password' => Hash::make($password),
            'raw_password' => $password,
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
        ]);
        if ($this->save) {
            $user->save();
        }
        return $user;
    }

    public function rules(): array
    {
        return [
            'nip' => ['required', 'string', Rule::unique('users', 'nip')],
            'name' => ['required', 'string'],
            'email' => ['required', 'string', Rule::unique('users', 'email')],
            'gender' => ['required', 'string'],
            // CRITICAL BUG FIX #7: Enforce existence validation for related records
            'education' => ['nullable', 'exists:educations,name'],
            'division' => ['nullable', 'exists:divisions,name'],
            'job_title' => ['nullable', 'exists:job_titles,name'],
            'password' => ['required', 'string', 'min:8'], // Enforce minimum password length
        ];
    }

    public function onFailure(Failure ...$failures)
    {
    }
}
