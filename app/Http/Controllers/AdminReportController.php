<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\SemesterApplicationExport;
use App\Exports\SemesterPaymentExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Application;
use App\Models\Semester;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        // Load all semesters sorted by latest session/semester
        $semesters = Semester::orderBy('session', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        // Prepare dropdown options
        $semesterOptions = [];
        foreach ($semesters as $s) {
            $key = $s->session . '-' . $s->semester;
            $label = $s->session . '/' . $s->semester;
            $semesterOptions[$key] = $label;
        }

        // Determine selected semester
        $selected = $request->get('session_semester');
        $session = null;
        $semester = null;

        if ($selected && strpos($selected, '-') !== false) {
            [$session, $semester] = explode('-', $selected);
            $semester = (int) $semester; // ensure numeric semester
        } else {
            $current = Semester::whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();

            if ($current) {
                $session = $current->session;
                $semester = $current->semester;
                $selected = $session . '-' . $semester;
            }
        }

        // Application statistics
        $totalApplications = Application::where('session', $session)->where('semester', $semester)->count();
        $approved = Application::where('session', $session)->where('semester', $semester)->where('application_status', 'approved')->count();
        $rejected = Application::where('session', $session)->where('semester', $semester)->where('application_status', 'rejected')->count();
        $pending = Application::where('session', $session)->where('semester', $semester)->where('application_status', 'pending')->count();
        $occupancy = Application::where('session', $session)
            ->where('semester', $semester)
            ->whereNotNull('room_allocation')
            ->where('application_status', 'approved')
            ->distinct('room_allocation')
            ->count();

        // Payment statistics
        $totalPaid = Payment::where('session', $session)
            ->where('semester', $semester)
            ->where('payment_status', 'paid')
            ->sum('amount');

        $paidCount = Payment::where('session', $session)
            ->where('semester', $semester)
            ->where('payment_status', 'paid')
            ->count();

        $pendingCount = Payment::where('session', $session)
            ->where('semester', $semester)
            ->where('payment_status', 'pending')
            ->count();

        // Hostel occupancy report
        $rooms = DB::table('rooms')
            ->leftJoin('applications', function ($join) use ($session, $semester) {
                $join->on('rooms.room_id', '=', 'applications.room_allocation')
                    ->where('applications.session', $session)
                    ->where('applications.semester', $semester)
                    ->where('applications.application_status', 'approved');
            })
            ->select(
                'rooms.hostel_id',
                'rooms.room_id',
                'rooms.room_status',
                DB::raw('COUNT(application_id) as assigned')
            )
            ->groupBy('rooms.hostel_id', 'rooms.room_id', 'rooms.room_status')
            ->get();

        $hostelStats = [];

        foreach ($rooms as $room) {
            $hostelId = $room->hostel_id;

            if (!isset($hostelStats[$hostelId])) {
                $hostelStats[$hostelId] = [
                    'available' => 0,
                    'allocated' => 0,
                    'maintenance' => 0,
                ];
            }

            if ($room->room_status === 'maintenance') {
                $hostelStats[$hostelId]['maintenance']++;
            } elseif ($room->assigned < 1) {
                $hostelStats[$hostelId]['available']++;
            } else {
                $hostelStats[$hostelId]['allocated']++;
            }
        }

        // Return to view
        return view('admin.reports.index', [
            'semesterOptions' => $semesterOptions,
            'selectedSemester' => $selected,
            'totalApplications' => $totalApplications,
            'approved' => $approved,
            'rejected' => $rejected,
            'pending' => $pending,
            'occupancy' => $occupancy,
            'totalPaid' => number_format($totalPaid, 2),
            'paidCount' => $paidCount,
            'pendingCount' => $pendingCount,
            'hostelStats' => $hostelStats,
        ]);
    }

    public function exportApplications($session, $semester)
    {
        $convertedSession = str_replace('-', '/', $session);
        $filename = 'applications_' . $session . '_sem' . $semester . '.xlsx';

        return Excel::download(new SemesterApplicationExport($convertedSession, $semester), $filename);
    }

    public function exportPayments($session, $semester)
    {
        $convertedSession = str_replace('-', '/', $session);
        $filename = 'payments_' . $session . '_sem' . $semester . '.xlsx';

        return Excel::download(new SemesterPaymentExport($convertedSession, $semester), $filename);
    }
}
