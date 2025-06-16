<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel; // For Excel class
use App\Imports\SemestersImport; // For the import class


class SemesterController extends Controller
{
    /**
     * Display a listing of the semesters.
     */
    public function index()
    {
        $semesters = Semester::orderBy('session', 'desc')->orderBy('semester')->paginate(10);
        return view('admin.semesters.index', compact('semesters'));

    }

    /**
     * Show the form for creating a new semester.
     */
    public function create()
    {
        return view('admin.semesters.create');
    }

    /**
     * Store a newly created semester in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'session' => 'required|string',
            'semester' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        Semester::create($request->all());

        return redirect()->route('semesters.index')->with('success', 'Semester created successfully.');
    }

    /**
     * Show the form for editing the specified semester.
     */
    public function edit($id)
    {
        $semester = Semester::findOrFail($id);
        return view('admin.semesters.edit', compact('semester'));
    }

    /**
     * Update the specified semester in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'session' => 'required|string|max:191',
            'semester' => 'required|integer|in:1,2,3',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $semester = Semester::findOrFail($id);
        $semester->update($request->only('session', 'semester', 'start_date', 'end_date'));

        return redirect()->route('semesters.index')->with('success', 'Semester updated successfully.');
    }

    /**
     * Remove the specified semester from storage.
     */
    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        $semester->delete();

return redirect()->route('semesters.index')->with('success', 'Semester deleted successfully.');
    }
   public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        //Excel::import(new SemestersImport, $file); // Make sure you have created this import class

        return redirect()->route('admin.semesters.index')->with('success', 'Semesters imported successfully!');
    }
    public function getSemesterSession(Request $request)
{
    $date = $request->date;

    $semester = Semester::where('start_date', '<=', $date)
        ->where('end_date', '>=', $date)
        ->first();

    if (!$semester) {
        return response()->json([
            'semester' => '',
            'session' => ''
        ], 404);
    }

    return response()->json([
        'semester' => $semester->semester,
        'session' => $semester->session
    ]);
}

}
