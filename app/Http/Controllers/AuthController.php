<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => ['required', 'email', 'max:254', 'not_regex:/[\r\n]/'], 'password' => 'required|string']);
        if (! Auth::attempt($credentials + ['role' => ['admin', 'editor', 'author']])) {
            throw ValidationException::withMessages(['email' => 'These credentials do not match an editorial account.']);
        }
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
