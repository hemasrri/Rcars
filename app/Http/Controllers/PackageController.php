<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Models\NonUthmUser;
class PackageController extends Controller
{
    // Display a listing of the packages
    public function index(Request $request)
    {
        $query = Package::query();

        // Optional: Add search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('package_name', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%')
                  ->orWhereRaw('CONCAT(package_name, " - ", category) LIKE ?', ['%' . $request->search . '%']);
            });
        }

        // Use paginate to get a maximum of 5 packages per page
        $packages = $query->paginate(5);

        return view('admin.packages.package', compact('packages'));
    }

    // Show the form for creating a new package
    public function create()
    {
        return view('admin.packages.create');
    }

    // Store a newly created package in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_name' => 'required|string|max:50',
            'details' => 'required|string',
            'category' => 'required|string|max:100',
            'price_per_day' => 'required|numeric',
        ]);

        // Generate custom ID: PCK001, PCK002, etc.
        $latest = Package::orderBy('id', 'desc')->first();
        if ($latest) {
            $lastNumber = (int)substr($latest->id, 3); // Remove 'PCK' and get the number
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newId = 'PCK' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Add the generated ID to validated data
        $validated['id'] = $newId;

        // Create the new package
        Package::create($validated);

        return redirect()->route('packages.index')->with('success', 'Package created successfully.');
    }

    // Show the form for editing the specified package
    public function edit($id)
    {
        $package = Package::findOrFail($id); // Fetch the package or fail
        return view('admin.packages.edit', compact('package'));
    }

    // Update the specified package in storage
    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id); // Fetch the package or fail

        $validated = $request->validate([
            'package_name' => 'required|string|max:50',
            'details' => 'required|string',
            'category' => 'required|string|max:100',
            'price_per_day' => 'required|numeric',
        ]);

        $package->update($validated); // Update the package with validated data

        return redirect()->route('packages.index')->with('success', 'Package updated successfully!');
    }

    // Remove the specified package from storage
    public function destroy($id)
    {
        $package = Package::findOrFail($id); // Fetch the package or fail
        $package->delete(); // Delete the package

        return redirect()->route('packages.index')->with('success', 'Package deleted successfully.');
    }

}