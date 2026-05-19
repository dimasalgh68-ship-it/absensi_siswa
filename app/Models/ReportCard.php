<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCard extends Model
{
    protected $fillable = ['student_id', 'semester', 'academic_year', 'total_score', 'average_score', 'notes', 'file_path'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
