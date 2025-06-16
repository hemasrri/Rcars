<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\ApplicationReceived;
use Illuminate\Support\Facades\Mail;
use App\Models\Admin;
use App\Models\Package;
use App\Models\Semester;
use App\Models\Application;
use App\Models\Hostel;
use App\Models\User;
use App\Notifications\NewApplicationReceived;
use App\Notifications\ApplicationAcknowledged;

class UserApplicationController extends Controller
{
    public function showApplicationForm()
    {
        $user = Auth::user();
        $categories = Package::select('category')->distinct()->pluck('category');
        $packages = Package::all();

        return view('users.application', compact('user', 'categories', 'packages'));
    }

    public function submitApplication(Request $request)
    {
        $action = $request->input('action'); // 'submit' or 'draft'

        $rules = [
            'name' => 'required|string|max:255',
            'ic_number' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'rental_purpose' => $action === 'submit' ? 'required|string' : 'nullable|string',
            'check_in_date' => $action === 'submit' ? 'required|date|after_or_equal:today' : 'nullable|date',
            'check_out_date' => $action === 'submit' ? 'required|date|after_or_equal:check_in_date' : 'nullable|date',
            'category' => $action === 'submit' ? 'required|string' : 'nullable|string',
            'package' => $action === 'submit' ? 'required|exists:packages,id' : 'nullable|exists:packages,id',
            'num_participants' => 'nullable|integer|min:0',
            'male' => 'nullable|integer|min:0',
            'female' => 'nullable|integer|min:0',
            'disabled_status' => 'nullable|in:yes,no',
            'disabled_male' => 'nullable|integer|min:0',
            'disabled_female' => 'nullable|integer|min:0',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ];

        $messages = [
            'check_in_date.after_or_equal' => 'Check-in date must be today or later.',
            'check_out_date.after_or_equal' => 'Check-out date must be same or after check-in date.',
            'package.exists' => 'Selected package is invalid or does not match the chosen category.',
        ];

        $validated = $request->validate($rules, $messages);

        $checkInDate = $validated['check_in_date'] ?? null;
        $semesterData = $checkInDate
            ? Semester::whereDate('start_date', '<=', $checkInDate)
                      ->whereDate('end_date', '>=', $checkInDate)
                      ->first()
            : null;

        if ($action === 'submit' && !$semesterData) {
            return back()->withErrors([
                'check_in_date' => 'No semester/session found for selected check-in date.'
            ])->withInput();
        }

        $semester = $semesterData->semester ?? 'N/A';
        $session = $semesterData->session ?? 'N/A';

        $packageModel = !empty($validated['package'])
            ? Package::where('id', $validated['package'])
                     ->where('category', $validated['category'])
                     ->first()
            : null;

        if ($action === 'submit' && !$packageModel) {
            return back()->withErrors([
                'package' => 'Selected package is invalid or mismatched.'
            ])->withInput();
        }

        $lastApp = Application::orderByDesc('application_id')->first();
        $nextId = $lastApp ? (int)substr($lastApp->application_id, 3) + 1 : 1;
        $applicationId = 'APP' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $documentPath = $request->hasFile('document')
            ? $request->file('document')->store('documents', 'public')
            : null;

        $application = new Application([
            'application_id' => $applicationId,
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'user_type' => 'non-uthm',
            'ic_number' => $validated['ic_number'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'semester' => $semester,
            'session' => $session,
            'rental_purpose' => $validated['rental_purpose'] ?? 'Draft - no purpose yet',
            'check_in_date' => $checkInDate,
            'check_out_date' => $validated['check_out_date'] ?? null,
            'num_participants' => $validated['num_participants'] ?? 0,
            'male' => $validated['male'] ?? 0,
            'female' => $validated['female'] ?? 0,
            'package' => $packageModel->id ?? $validated['package'] ?? null,
            'document_path' => $documentPath,
            'application_status' => $action === 'submit' ? 'pending' : 'draft',
            'disabled_male' => ($validated['disabled_status'] ?? 'no') === 'yes' ? ($validated['disabled_male'] ?? 0) : 0,
            'disabled_female' => ($validated['disabled_status'] ?? 'no') === 'yes' ? ($validated['disabled_female'] ?? 0) : 0,
        ]);

        $application->save();

        if ($action === 'submit') {
            $admins = Admin::all();
            foreach ($admins as $admin) {
                $admin->notify(new NewApplicationReceived($application));
            }

            $user = User::find(Auth::id());
            $user->notify(new ApplicationAcknowledged($application));
        }

        return redirect()
            ->route('users.dashboard')
            ->with('success', $action === 'submit' ? 'Application submitted!' : 'Draft saved.');
    }

    public function fetchPackages(Request $request)
    {
        $packages = Package::when($request->input('category'), function ($query, $category) {
            return $query->where('category', $category);
        })->get(['id', 'package_name', 'details', 'price_per_day']);

        return response()->json($packages);
    }

    public function getSemesterSession(Request $request)
    {
        $date = $request->input('date');

        $semester = Semester::whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

        return $semester
            ? response()->json(['semester' => $semester->semester, 'session' => $semester->session])
            : response()->json([], 404);
    }

    public function show($application_id)
    {
        $application = Application::findOrFail($application_id);
        $user = Auth::user();
        $package = Package::find($application->package);

        return view('users.view-application', compact('application', 'user', 'package'));
    }

public function edit($application_id)
{
    // Load the application with its related package
    $application = Application::with('packageModel')->findOrFail($application_id);
    
    // Get the currently authenticated user
    $user = Auth::user();

    // Fetch distinct categories from packages
    $categories = Package::select('category')->distinct()->get();

    // Get the selected category from the package model
    $selectedCategory = $application->packageModel?->category ?? null;

    // Fetch packages based on the selected category
    $packages = $selectedCategory
        ? Package::where('category', $selectedCategory)->get()
        : collect();

    // Return the view with the necessary data
    return view('users.edit-application', compact(
        'application', 'user', 'categories', 'packages', 'selectedCategory'
    ));
}

public function update(Request $request, $application_id)
{
    // Find the application or fail
    $application = Application::findOrFail($application_id);
    $action = $request->input('action');

    // Define validation rules
    $rules = [
        'name' => 'required|string|max:255',
        'ic_number' => 'required|string|max:20',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255',
        'rental_purpose' => $action === 'submit' ? 'required|string' : 'nullable|string',
        'check_in_date' => $action === 'submit' ? 'required|date|after_or_equal:today' : 'nullable|date',
        'check_out_date' => $action === 'submit' ? 'required|date|after_or_equal:check_in_date' : 'nullable|date',
        'category' => $action === 'submit' ? 'required|string' : 'nullable|string',
        'package_id' => $action === 'submit' ? 'required|exists:packages,id' : 'nullable|exists:packages,id',
        'num_participants' => 'nullable|integer|min:0',
        'male' => 'nullable|integer|min:0',
        'female' => 'nullable|integer|min:0',
        'disabled_status' => 'nullable|in:yes,no',
        'disabled_male' => 'nullable|integer|min:0',
        'disabled_female' => 'nullable|integer|min:0',
        'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
    ];

    // Validate the request
    $validated = $request->validate($rules);

    // Check semester data based on check-in date
    $checkInDate = $validated['check_in_date'] ?? null; // Set to null if not provided
    if ($checkInDate) {
        $semesterData = Semester::whereDate('start_date', '<=', $checkInDate)
                                ->whereDate('end_date', '>=', $checkInDate)
                                ->first();

        // Validate semester data if submitting
        if ($action === 'submit' && !$semesterData) {
            return back()->withErrors(['check_in_date' => 'No semester/session found for the selected check-in date.'])->withInput();
        }
    }

    // Validate package model if submitting
    $packageModel = !empty($validated['package_id'])
        ? Package::where('id', $validated['package_id'])
                  ->where('category', $validated['category'] ?? $application->category)
                  ->first()
        : null;

    if ($action === 'submit' && !$packageModel) {
        return back()->withErrors(['package' => 'The selected package does not match the selected category.'])->withInput();
    }

    // Handle document upload
    if ($request->hasFile('document')) {
        $documentPath = $request->file('document')->store('documents', 'public');
        $application->document_path = $documentPath;
    }

    // Fill application data
    $application->fill([
        'name' => $validated['name'],
        'ic_number' => $validated['ic_number'],
        'phone' => $validated['phone'],
        'email' => $validated['email'],
        'rental_purpose' => $validated['rental_purpose'] ?? $application->rental_purpose,
        'check_in_date' => $checkInDate ?? $application->check_in_date,
        'check_out_date' => $validated['check_out_date'] ?? $application->check_out_date,
        'category' => $validated['category'] ?? $application->category,
        'package' => $validated['package_id'] ?? $application->package,
        'num_participants' => $validated['num_participants'] ?? 0,
        'male' => $validated['male'] ?? 0,
        'female' => $validated['female'] ?? 0,
        'disabled_male' => ($validated['disabled_status'] ?? 'no') === 'yes' ? ($validated['disabled_male'] ?? 0) : 0,
        'disabled_female' => ($validated['disabled_status'] ?? 'no') === 'yes' ? ($validated['disabled_female'] ?? 0) : 0,
        'semester' => $semesterData->semester ?? $application->semester,
        'session' => $semesterData->session ?? $application->session,
        'application_status' => $action === 'submit' ? 'pending' : 'draft',
    ]);

    // Save the application
    $application->save();

    // Notify user if submitted
    if ($action === 'submit' && $application->user) {
        $application->user->notify(new ApplicationAcknowledged($application));
    }

    // Redirect with success message
    return redirect()->route('users.dashboard')->with('success',
        $action === 'submit' ? 'Application submitted successfully!' : 'Draft saved successfully.');
}



    public function downloadPdf($application_id)
    {
        $application = Application::findOrFail($application_id);
        $package = Package::find($application->package);

        $pdf = Pdf::loadView('users.pdf', compact('application', 'package'));

        return $pdf->download('application_' . $application->application_id . '.pdf');
    }
}
