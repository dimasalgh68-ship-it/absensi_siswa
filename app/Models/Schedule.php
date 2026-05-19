<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['teacher_id', 'subject_id', 'education_id', 'day', 'start_time', 'end_time', 'room'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function education()
    {
        return $this->belongsTo(Education::class);
    }
}
