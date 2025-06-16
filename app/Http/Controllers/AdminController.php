<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $admin = Auth::guard('admin')->user();

        if ($admin instanceof Admin) {
            // Consider moving this to login logic if you don't want to update every visit
            $admin->last_login_at = now();
            $admin->save();
        }

        // Optimize room counts per hostel using withCount()
        $hostels = Hostel::withCount([
            'rooms as available_rooms' => fn($query) => $query->where('room_status', 'available'),
            'rooms as maintenance_rooms' => fn($query) => $query->where('room_status', 'maintenance'),
            'rooms as allocated_rooms' => fn($query) => $query->where('room_status', 'allocated'),
        ])->get();

        $totalRooms = Hostel::sum('total_rooms');
        $availableRooms = Room::where('room_status', 'available')->count();
        $maintenanceRooms = Room::where('room_status', 'maintenance')->count();
        $allocatedRooms = Room::where('room_status', 'allocated')->count();

        $pendingApplicationsCount = Application::where('application_status', 'pending')->count();

        // Applications in the last 30 days
        $newApplicationsCount = Application::where('created_at', '>=', Carbon::now()->subDays(30))
                                           ->where('application_status', '!=', 'draft')
                                           ->count();

        $rejectedApplicationsCount = Application::where('application_status', 'rejected')->count();

        // Uncommented total revenue calculation
        $totalRevenue = Application::where('application_status', 'approved')
                                   ->sum('payment_amount');

        return view('admin.dashboard', compact(
            'hostels',
            'totalRooms',
            'availableRooms',
            'maintenanceRooms',
            'allocatedRooms',
            'pendingApplicationsCount',
            'newApplicationsCount',
            'rejectedApplicationsCount',
            'totalRevenue'
        ));
    }

    public function userManagement()
    {
        $users = Admin::all();
        return view('admin.user-management', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|string|unique:admin,staff_id',
            'staff_name' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Admin::create([
            'staff_id' => $request->staff_id,
            'staff_name' => $request->staff_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.user-management')->with('success', 'User added successfully.');
    }

    public function destroy($id)
    {
        $user = Admin::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user-management')->with('success', 'User deleted successfully.');
    }

   public function changePassword(Request $request, $id)
{
    $request->validate([
        'new_password' => 'required|string|min:6|confirmed',  // Laravel will check 'new_password_confirmation'
    ]);

    $user = Admin::findOrFail($id);
    $user->password = Hash::make($request->new_password);
    $user->save();

    return redirect()->route('admin.user-management')->with('success', 'Password changed successfully.');
}

public function markAllRead()
{
    $admin = auth('admin')->user();
    $admin->unreadNotifications->markAsRead();

    return back()->with('success', 'All notifications marked as read.');
}
public function updateEmail(Request $request, $id)
{
    $request->validate(['email' => 'required|email']);

    $admin = Admin::findOrFail($id);
    $admin->email = $request->email;
    $admin->save();

    return redirect()->route('admin.user-management')->with(['success' => true, 'message' => 'Email updated successfully.']);

}

public function update(Request $request, $staff_id)
{
    $request->validate([
        'staff_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'password' => 'nullable|string|min:6|confirmed', // allow optional password update
    ]);

    $admin = Admin::where('staff_id', $staff_id)->firstOrFail();

    $admin->staff_name = $request->input('staff_name');
    $admin->email = $request->input('email');

    if ($request->filled('password')) {
        $admin->password = Hash::make($request->input('password'));
    }

    $admin->save();

    return redirect()->route('admin.user-management')->with('success', 'Admin info updated successfully.');
}



   
}
