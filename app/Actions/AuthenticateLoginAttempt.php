<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticateLoginAttempt
{
    public function __invoke(Request $request)
    {
        $login = $request->input('email'); // Fortify uses 'email' field by default

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $login)->first();
        } else {
            // Try NISN first, then Phone
            $user = User::where('nisn', $login)
                ->orWhere('phone', $login)
                ->first();
        }

        if ($user && Hash::check($request->password, $user->password)) {
            return $user;
        }

        return null;
    }
}
