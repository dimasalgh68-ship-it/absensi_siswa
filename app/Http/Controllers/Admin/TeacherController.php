<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // SECURITY: Only admin can access teacher data
        if (!Auth::check() || !Auth::user()->isAdmin) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses data guru.');
        }

        return view('admin.teachers.index');
    }
}
