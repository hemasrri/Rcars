<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;

class AjaxController extends Controller
{
    /**
     * Return packages based on the selected category via AJAX.
     */
    public function getPackagesByCategory(Request $request)
    {
        $category = $request->input('category');

        if (!$category) {
            return response()->json([], 400); // Bad request if no category provided
        }

        $packages = Package::where('category', $category)->get(['id', 'package_name', 'details', 'price_per_day']);

        return response()->json($packages);
    }

    /**
     * Optional: Get semester and session by check-in date.
     */
    public function getSemesterSession(Request $request)
    {
        $date = $request->input('date');

        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        $record = \App\Models\Semester::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first(['semester', 'session']);

        if ($record) {
            return response()->json([
                'semester' => $record->semester,
                'session' => $record->session,
            ]);
        }

        return response()->json(['error' => 'Not found'], 404);
    }
}
