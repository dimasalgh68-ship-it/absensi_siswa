<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'code'];

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Get the teachers who teach this subject
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject');
    }
}
