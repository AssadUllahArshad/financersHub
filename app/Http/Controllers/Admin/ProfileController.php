<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('admin.profile', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $request->merge(['name' => trim((string) $request->input('name')), 'email' => strtolower(trim((string) $request->input('email')))]);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:254', 'not_regex:/[\r\n]/', Rule::unique('users')->ignore($request->user()->id)],
            'current_password' => ['required', 'current_password'],
        ]);
        $user = $request->user();
        if ($user->email !== $data['email']) {
            $user->email_verified_at = null;
        }
        $user->fill(collect($data)->only(['name', 'email'])->all())->save();
        $request->session()->regenerate();

        return redirect()->route('admin.profile')->with('status', 'Your profile has been updated. Use the updated email for your next sign-in.');
    }

    public function password(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'max:72', 'confirmed', 'different:current_password', Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);
        $request->user()->forceFill(['password' => $data['password'], 'remember_token' => Str::random(60)])->save();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Password updated. Please sign in with your new password.');
    }
}
