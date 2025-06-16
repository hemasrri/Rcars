<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Application;
use App\Models\Hostel;
use App\Models\Package;
use App\Models\User;
use App\Notifications\ApplicationStatusUpdated;

class UserController extends Controller
{
 public function index(Request $request)
{
    $user = auth()->user(); // Get current authenticated user

    $notifications = $user->notifications;
    $unreadCount = $user->unreadNotifications->count();

    $upcomingBookings = Application::where('user_id', $user->id)
        ->whereRaw('LOWER(application_status) = ?', ['accepted'])
        ->whereDate('check_in_date', '>=', now()->toDateString())
        ->count();

    $pendingApplications = Application::where('user_id', $user->id)
        ->whereRaw('LOWER(application_status) = ?', ['pending'])
        ->count();

    $perPage = $request->input('per_page', 5);

    $applications = Application::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);

    $hostels = Hostel::all();

    return view('users.dashboard', compact(
        'notifications',
        'unreadCount',
        'hostels',
        'applications',
        'upcomingBookings',
        'pendingApplications'
    ));
}

    public function edit($application_id)
    {
        $application = Application::findOrFail($application_id);
        $user = Auth::user(); 
        return view('users.edit', compact('application', 'user'));
    }

    public function view($application_id)
    {
        $application = Application::where('application_id', $application_id)->firstOrFail();

        $package = $application->package
            ? Package::where('id', $application->package)->first()
            : null;

        return view('users.view', compact('application', 'package'));
    }

    public function account()
    {
        $user = auth()->user();
        return view('users.account', compact('user'));
    }

    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'user_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'ic_number' => 'nullable|string|max:20',
    ]);

    $user->fill([
        'user_name' => $request->user_name,
        'email' => $request->email,
        'phone' => $request->phone,
        'ic_number' => $request->ic_number,
    ])->save();

    return redirect()->route('users.account')->with('success', 'Profile updated successfully.');
}


    public function changePassword(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    $user->password = Hash::make($request->new_password);
    $user = auth()->user(); // This avoids Intelephense warning

    return redirect()->route('users.account')->with('success', 'Password changed successfully.');

}
public function printQuotation($application_id)
{
    $application = Application::findOrFail($application_id);

    // Add package/hostel if needed
    $package = $application->package ? Package::find($application->package) : null;
    $user = auth()->user();

return view('users.quotation', compact('user', 'application', 'package'));
}

public function changeAdminPassword(Request $request)
{
    $admin = Auth::guard('admin')->user();

    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    if (!Hash::check($request->current_password, $admin->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    $admin->password = Hash::make($request->new_password);
    $admin->save();

    return redirect()->route('admin.account')->with('success', 'Password changed successfully.');
}


}
