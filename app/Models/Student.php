<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = [
        'user_id',
        'nis',
        'class',
        'status',
        'enrollment_date',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
    ];

    /**
     * Get the user associated with this student
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendances for this student
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the grades for this student
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the task submissions for this student
     */
    public function taskSubmissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    /**
     * Scope to get active students
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get inactive students
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
