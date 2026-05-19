<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    protected $fillable = ['task_id', 'user_id', 'answer', 'status', 'file_path', 'link', 'submitted_at'];

    protected $casts = [
        'status' => 'string',
        'submitted_at' => 'datetime',
    ];

    // BUG FIX: Add validation rules
    public static function rules()
    {
        return [
            'task_id' => 'required|exists:tasks,id',
            'user_id' => 'required|exists:users,id',
            'answer' => 'nullable|string|max:5000',
            'status' => 'required|in:pending,approved,rejected',
            'file_path' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:500',
            'submitted_at' => 'required|date',
        ];
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
