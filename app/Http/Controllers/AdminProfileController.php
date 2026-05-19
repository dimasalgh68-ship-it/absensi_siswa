<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    /**
     * Show the admin profile page
     */
    public function show()
    {
        // Ensure user is authenticated and is an admin
        if (!Auth::check() || !Auth::user()->isAdmin) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return view('admin.profile');
    }
}
