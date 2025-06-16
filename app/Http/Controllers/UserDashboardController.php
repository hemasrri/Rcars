<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Application;
use App\Models\Hostel;
use App\Models\Package;

class UserDashboardController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('web')->user(); // or use the appropriate guard

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        $userId = $user->user_id;

        // Booking stats
        $upcomingBookings = Application::where('user_id', $userId)
            ->whereRaw('LOWER(application_status) = ?', ['accepted'])
            ->whereDate('check_in_date', '>=', now()->toDateString())
            ->count();

        $pendingApplications = Application::where('user_id', $userId)
            ->whereRaw('LOWER(application_status) = ?', ['pending'])
            ->count();

        Log::info('Logged in user: ' . json_encode($user));

        $perPage = $request->input('per_page', 5);
        $applications = Application::with(['payment' => function ($query) {
            $query->where('payment_status', 'successful');
        }])->where('user_id', $userId)->paginate($perPage);

        $hostels = Hostel::all();

        return view('user.dashboard', compact(
            'user',
            'applications', // Corrected variable name
            'upcomingBookings',
            'pendingApplications',
            'hostels'
        ));
    }
    /**
     * Show the edit form for a specific application.
     */
   public function edit($application_id)
{
    $application = Application::findOrFail($application_id);
    $user = Auth::guard('web')->user();
    $categories = Package::select('category')->distinct()->get();
    $packages = Package::all();
    $hostels = Hostel::all(); // ✅ Fix: add this line

    return view('user.edit', compact('application', 'user', 'categories', 'packages', 'hostels'));
}

    /**
     * Show a specific application and its package.
     */
    public function view($application_id)
    {
        $application = Application::where('application_id', $application_id)->firstOrFail();

        $package = null;
        if ($application->package) {
            $package = Package::find($application->package);
        }

        return view('user.view', compact('application', 'package'));
    }
}
