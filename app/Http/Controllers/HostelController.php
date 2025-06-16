<?php

namespace App\Http\Controllers;

use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HostelController extends Controller
{
    // Display a listing of the hostels
    public function index(Request $request)
    {
        $query = Hostel::query();
    
        if ($request->has('search') && !empty($request->search)) {
            $query->where('hostel_name', 'like', '%' . $request->search . '%');
        }
    
        $hostels = $query->paginate(5);
    
        return view('admin.hostels.hostels', compact('hostels'));
    }
    

    // Show the form for creating a new hostel
    public function create()
    {
        return view('admin.hostels.add-hostel');
    }

    // Store a newly created hostel in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hostel_name' => 'required|string|max:191',
            'total_blocks' => 'required|integer|min:1',
            'total_rooms' => 'required|integer|min:1',
            'gender' => 'required|in:male,female,mix', // updated from hostel_type
            'image' => 'nullable|image|max:2048',
            'facilities' => 'nullable|string',
        ]);

        $hostelId = 'hst' . str_pad(Hostel::count() + 1, 3, '0', STR_PAD_LEFT);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hostel_images', 'public');
        }

        Hostel::create([
            'hostel_id' => $hostelId,
            'hostel_name' => $validated['hostel_name'],
            'total_blocks' => $validated['total_blocks'],
            'total_rooms' => $validated['total_rooms'],
            'gender' => $validated['gender'], // updated
            'image' => $validated['image'] ?? null,
            'facilities' => $validated['facilities'],
        ]);

        return redirect()->route('hostels.store')->with('success', 'Hostel created successfully.');
    }

    // Show the form for editing the specified hostel
    public function edit($id)
    {
        $hostel = Hostel::findOrFail($id);
        return view('admin.hostels.edit-hostel', compact('hostel'));
    }

    // Update the specified hostel in storage
   public function update(Request $request, $id)
{
    $hostel = Hostel::findOrFail($id);

    $validated = $request->validate([
        'hostel_name'   => 'required|string|max:191',
        'total_blocks'  => 'required|integer|min:1',
        'total_rooms'   => 'required|integer|min:1',
        'gender'        => 'required|in:male,female,mix',
        'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'facilities'    => 'nullable|string',
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        // Delete old image if it exists
        if ($hostel->image && Storage::disk('public')->exists($hostel->image)) {
            Storage::disk('public')->delete($hostel->image);
        }

        // Store new image
        $path = $request->file('image')->store('hostel_images', 'public');
        $validated['image'] = $path;
    } else {
        // Retain existing image if no new upload
        $validated['image'] = $hostel->image;
    }

    // Update the hostel record
    $hostel->update([
        'hostel_name'   => $validated['hostel_name'],
        'total_blocks'  => $validated['total_blocks'],
        'total_rooms'   => $validated['total_rooms'],
        'gender'        => $validated['gender'],
        'image'         => $validated['image'],
        'facilities'    => $validated['facilities'] ?? null,
    ]);

    return redirect()->route('hostels.index')->with('success', 'Hostel updated successfully.');
}
    // Remove the specified hostel from storage
    public function destroy($id)
    {
        $hostel = Hostel::findOrFail($id);

        if ($hostel->image) {
            Storage::disk('public')->delete($hostel->image);
        }

        $hostel->delete();

        return redirect()->route('hostels.index')->with('success', 'Hostel deleted successfully.');
    }
}
