<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $fillable = ['exam_id', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'correct_option'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
