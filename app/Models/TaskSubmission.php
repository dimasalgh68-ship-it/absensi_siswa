<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    protected $fillable = ['task_id', 'user_id', 'answer', 'status', 'file_path', 'link', 'submitted_at'];

    protected $casts = [
        'status' => 'string',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
