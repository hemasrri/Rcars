<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    

    public function login(Request $request)
{
    $request->validate([
        'user_type' => 'required|in:admin,uthm,non-uthm',
        'identifier' => 'required',
        'password' => 'required',
    ]);

    $remember = $request->has('remember');

    if ($request->user_type === 'admin') {
        if (Auth::guard('admin')->attempt([
            'staff_id' => $request->identifier,
            'password' => $request->password,
        ], $remember)) {

            /** @var Admin $admin */
            $admin = Auth::guard('admin')->user();
            $admin->last_login_at = now();
            $admin->save();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['login' => 'Invalid admin credentials']);
    }

    $user = User::where(function ($query) use ($request) {
            $query->where('email', $request->identifier)
                  ->orWhere('ic_number', $request->identifier)
                  ->orWhere('user_id', $request->identifier);
        })
        ->where('user_type', $request->user_type)
        ->first();

    if ($user && Hash::check($request->password, $user->password)) {

        // Email verification check only for non-uthm users
        if ($request->user_type === 'non-uthm' && is_null($user->email_verified_at)) {
            return back()->withErrors(['login' => 'Please verify your email before logging in.']);
        }

        Auth::guard('web')->login($user, $remember);

        /** @var User $user */
        $user->update(['last_login_at' => now()]);

        return redirect()->route('users.dashboard');
    }

    return back()->withErrors(['login' => 'Invalid credentials or user type']);
}

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
