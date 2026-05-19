<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasUlids;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nisn',
        'name',
        'email',
        'password',
        'raw_password',
        'group',
        'phone',
        'gender',
        'birth_date',
        'birth_place',
        'address',
        'city',
        'education_id',
        'division_id',
        'job_title_id',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'raw_password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'datetime:Y-m-d',
            'password' => 'hashed',
        ];
    }

    public static $groups = ['user', 'admin', 'superadmin', 'teacher', 'student'];

    protected function isUser(): Attribute
    {
        return Attribute::get(fn () => $this->group === 'user');
    }

    protected function isAdmin(): Attribute
    {
        return Attribute::get(fn () => $this->group === 'admin' || $this->group === 'superadmin');
    }

    protected function isSuperadmin(): Attribute
    {
        return Attribute::get(fn () => $this->group === 'superadmin');
    }

    protected function isTeacher(): Attribute
    {
        return Attribute::get(fn () => $this->group === 'teacher');
    }

    protected function isStudent(): Attribute
    {
        return Attribute::get(fn () => $this->group === 'student');
    }

    protected function isNotAdmin(): Attribute
    {
        return Attribute::get(fn () => $this->group !== 'admin' && $this->group !== 'superadmin');
    }

    final public function canManageStudents(): bool
    {
        return $this->isAdmin || $this->isTeacher;
    }

    final public function canManageFaceRegistration(): bool
    {
        return $this->isAdmin || $this->isTeacher;
    }

    final public function canViewReports(): bool
    {
        return $this->isAdmin || $this->isTeacher;
    }

    final public function canExportData(): bool
    {
        return $this->isAdmin || $this->isTeacher;
    }

    public function education()
    {
        return $this->belongsTo(Education::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function faceRegistration()
    {
        return $this->hasOne(FaceRegistration::class);
    }

    public function faceRegistrations()
    {
        return $this->hasMany(FaceRegistration::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function taskSubmissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }
}
