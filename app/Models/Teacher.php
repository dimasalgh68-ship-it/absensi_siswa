<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'specialization',
        'certification_number',
        'certification_date',
        'status',
    ];

    protected $casts = [
        'certification_date' => 'date',
    ];

    /**
     * Get the user associated with this teacher
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the materials created by this teacher
     */
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    /**
     * Get the schedules for this teacher
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the exams created by this teacher
     */
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Get the grades given by this teacher
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the subjects taught by this teacher
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject');
    }

    /**
     * Scope to get active teachers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get inactive teachers
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
