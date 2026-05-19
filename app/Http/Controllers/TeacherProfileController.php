<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class TeacherProfileController extends Controller
{
    /**
     * Show the teacher profile page
     */
    public function show()
    {
        // Ensure user is authenticated and is a teacher
        if (!Auth::check() || !Auth::user()->isTeacher) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $teacher = Auth::user()->teacher;

        return view('teacher.profile', [
            'teacher' => $teacher,
        ]);
    }
}
