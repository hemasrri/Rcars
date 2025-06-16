<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Semester;
use App\Models\Admin;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\NewApplicationReceived;
use Illuminate\Support\Facades\Notification;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('application_id', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%')
                  ->orWhere('application_status', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('application_status', $request->status);
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(5);

        return view('admin.applications.application', compact('applications'));
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'user_id'           => 'required|integer',
        'user_type'         => 'required|string',
        'ic_number'         => 'required|string',
        'matrix_number'     => 'required|string',
        'phone'             => 'required|string',
        'email'             => 'required|email',
        'rental_purpose'    => 'required|string',
        'check_in_date'     => 'required|date',
        'check_out_date'    => 'required|date',
        'num_participants'  => 'required|integer',
        'male'              => 'required|integer',
        'female'            => 'required|integer',
        'disabled_male'     => 'required|integer',
        'disabled_female'   => 'required|integer',
        'package_id'        => 'required|integer',
    ]);

    $applicationId = $this->generateApplicationId();

    // Fetch semester and session based on current date
    $now = Carbon::now()->toDateString();
    $semester = Semester::where('start_date', '<=', $now)
                        ->where('end_date', '>=', $now)
                        ->first();

    if (!$semester) {
        return redirect()->back()->with('error', 'Semester and session data not found for the current date.');
    }

    $application = new Application();
    $application->fill($validatedData);
    $application->application_id = $applicationId;
    $application->application_status = 'pending';
    $application->semester = $semester->semester;
    $application->session = $semester->session;
    $application->save();


return redirect()->route('admin.applications.index')->with('success', 'Application submitted successfully.');
}


public function approve(Request $request, Application $application)
{
    Log::info('Approving application', ['application_id' => $application->application_id]);

    // Fetch the package associated with the application
   $package = Package::where('id', $application->package)->first();


; // Ensure you are using the correct field
    if (!$package) {
        return redirect()->route('admin.applications.index')->with('error', 'Invalid package.');
    }

    // Check-in and check-out validation
    $checkIn = Carbon::parse($application->check_in_date);
    $checkOut = Carbon::parse($application->check_out_date);

    if ($checkIn->greaterThanOrEqualTo($checkOut)) {
        return redirect()->route('admin.applications.index')->with('error', 'Invalid check-in/check-out dates.');
    }

    // Update fee_exception/payment_exception flag from request
    $application->payment_exception = $request->has('payment_exception');


    if (!$application->payment_exception) {
        // Calculate the number of days and payment amount
        $days = $checkIn->diffInDays($checkOut);
        $paymentAmount = $days * $application->num_participants * $package->price_per_day;

        // Create a new payment instance
        $payment = new Payment();
        $payment->payment_id = (string) Str::uuid(); // Generate a unique payment ID
        $payment->application_id = $application->application_id; // Link to the application
        $payment->amount = $paymentAmount; // Set the payment amount
        $payment->payment_status = 'pending'; // Set initial payment status
        $payment->payment_date = now(); // Set the payment date
        $payment->semester = $application->semester; // Set the semester from the application
        $payment->session = $application->session; // Set the session from the application
        $payment->payment_method = 'Not yet paid'; // Set payment method as 'Not yet paid'

        // Save the payment record
        $payment->save();

        $application->payment_amount = $paymentAmount; // Store the payment amount in the application
    } else {
        // If payment exception, no payment required, so set amount 0 or null
        $application->payment_amount = 0;
    }

    // Clear rejection reason and set processed details
    $application->rejection_reason = null;
    $application->processed_by = Auth::guard('admin')->user()->staff_name ?? 'System';
    $application->processed_at = now();

    // Allocate rooms for the application
    $roomAllocation = $this->allocateRoom($application);
    if (!$roomAllocation) {
        return redirect()->route('admin.applications.index')->with('error', 'No available room for allocation.');
    }

    // Prepare room allocation text
    $roomText = collect($roomAllocation)->map(function ($room, $type) {
        return "$type: $room";
    })->implode("\n");

    // Update application status and room allocation
    $application->room_allocation = $roomText;
    $application->application_status = 'approved'; // Update application status to accepted

    $application->save(); // Save the updated application
$application->user->notify(new \App\Notifications\ApplicationStatusUpdated($application));

    return redirect()->route('admin.applications.view')->with('success', 'Application approved and rooms allocated.');
}



    public function reject(Application $application, Request $request)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $roomAllocations = null;
        if (!empty($application->room_allocation)) {
            $roomAllocations = [];
            $lines = explode("\n", trim($application->room_allocation));
            foreach ($lines as $line) {
                [$type, $room] = array_map('trim', explode(':', $line));
                $roomAllocations[$type] = [$room];
            }
        }

        $application->update([
            'application_status' => 'rejected',
            'rejection_reason'   => $request->rejection_reason,
            'processed_by'       => Auth::guard('admin')->user()->staff_name ?? 'System',
            'processed_at'       => now(),
            'room_allocation'    => null,
        ]);

        if (is_array($roomAllocations)) {
            foreach ($roomAllocations as $type => $rooms) {
                foreach ($rooms as $roomNumber) {
                    $room = Room::where('room_number', $roomNumber)->first();
                    if ($room && $room->room_status === 'allocated') {
                        $room->update(['room_status' => 'available']);
                    }
                }
            }
        }

        return redirect()->route('admin.applications.index')->with('success', 'Application rejected');
    }

    private function allocateRoom(Application $application)
    {
        $alloc = [];

        // Disabled male → ground floor
        if ($application->disabled_male > 0) {
            $room = Room::where('room_status', 'available')->where('gender', 'male')->where('floor_number', 0)->first();
            if ($room) {
                $room->update(['room_status' => 'allocated']);
                $alloc['disabled_male'] = $room->room_number;
            }
        }

        if ($application->male > 0) {
            $room = Room::where('room_status', 'available')->where('gender', 'male')->where('floor_number', '>', 0)->first();
            if (!$room) return null;
            $room->update(['room_status' => 'allocated']);
            $alloc['male'] = $room->room_number;
        }

        if ($application->disabled_female > 0) {
            $room = Room::where('room_status', 'available')->where('gender', 'female')->where('floor_number', 0)->first();
            if ($room) {
                $room->update(['room_status' => 'allocated']);
                $alloc['disabled_female'] = $room->room_number;
            }
        }

        if ($application->female > 0) {
            $room = Room::where('room_status', 'available')->where('gender', 'female')->where('floor_number', '>', 0)->first();
            if (!$room) return null;
            $room->update(['room_status' => 'allocated']);
            $alloc['female'] = $room->room_number;
        }

        return $alloc;
    }

    private function generateApplicationId()
    {
        $latest = Application::orderBy('created_at', 'desc')->first();
        $number = $latest ? intval(substr($latest->application_id, 3)) + 1 : 1;
        return 'APP' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

   public function download(Request $request)
{
    $applications = Application::query();

    if ($request->filled('search')) {
        $applications->where(function ($query) use ($request) {
            $query->where('application_id', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('status')) {
        $applications->where('application_status', $request->status);
    }

    // Exclude applications with status = 'draft'
    $applications->where('application_status', '!=', 'draft');

    $applications = $applications->get();

    $csvFileName = 'applications_' . date('Y-m-d') . '.csv';
    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$csvFileName",
    ];

    $callback = function () use ($applications) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Applicant ID', 'Name', 'Email', 'Phone', 'Date Applied', 'Check In', 'Check Out', 'Status', 'Disabled Male', 'Disabled Female']);

        foreach ($applications as $app) {
            fputcsv($handle, [
                $app->application_id,
                $app->name,
                $app->email,
                $app->phone,
                $app->created_at->format('Y-m-d'),
                $app->check_in_date->format('Y-m-d'),
                $app->check_out_date->format('Y-m-d'),
                ucfirst($app->application_status),
                $app->disabled_male,
                $app->disabled_female,
            ]);
        }

        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
}


    public function edit($application_id)
    {
        $application = Application::findOrFail($application_id);
        $packages = Package::all();
        $categories = $packages->pluck('category')->unique();

        return view('non-uthm-user.edit', compact('application', 'packages', 'categories'));
    }

    public function update(Request $request, Application $application)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            // Add more validations as needed
        ]);

        $application->update($validatedData);

        return redirect()->route('admin.applications.index')
                         ->with('success', 'Application updated successfully.');
    }

   public function destroy($id)
{
    $application = Application::findOrFail($id);

    if ($application->application_status !== 'draft') {
        return redirect()->back()->with('error', 'You can only delete draft applications.');
    }

    $application->delete();

    return redirect()->route('users.dashboard')->with('success', 'Application deleted successfully.');
}


}

