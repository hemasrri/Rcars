<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered; // Import event
use Illuminate\Support\Facades\Auth;  // Add at top


class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register'); // Make sure this view exists
    }

    public function register(Request $request)
{
    // Validate input data
    $request->validate([
        'name' => 'required|string|max:255',
        'ic' => 'required|string|max:20|unique:users,ic_number',
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'required|string|max:15',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // Create new user
    $user = new User();
    $user->user_name = $request->name;
    $user->ic_number = $request->ic;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->password = Hash::make($request->password);
    $user->user_type = 'non-uthm';
    $user->user_id = Str::random(10);

    $user->save();

    // Log in the user immediately
    Auth::login($user);

    // Fire the Registered event to send the email verification notification
    event(new Registered($user));

    // Redirect to email verification notice page (instead of login directly)
    return redirect()->route('verification.notice')->with('success', 'Account created successfully! Please check your email to verify your account before logging in.');
}

}
