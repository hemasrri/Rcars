<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SemesterApplicationExport implements FromCollection, WithHeadings, WithMapping
{
    protected $session;
    protected $semester;

    public function __construct($session, $semester)
    {
        $this->session = $session;
        $this->semester = $semester;
    }

    /**
     * Fetch filtered applications for export.
     */
    public function collection()
    {
        return Application::where('session', $this->session)
            ->where('semester', $this->semester)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Define Excel column headings.
     */
    public function headings(): array
    {
        return [
            'Application ID',
            'Name',
            'User Type',
            'Session',
            'Semester',
            'Application Status',
            'Room Allocation',
            'Package',
            'Payment Exception',
            'Total Participants',
            'Processed By',
            'Processed At',
            'Submitted At',
        ];
    }

    /**
     * Map application data to Excel row.
     */
    public function map($application): array
    {
        return [
            $application->application_id,
            $application->name,
            ucfirst($application->user_type),
            $application->session,
            $application->semester,
            ucfirst($application->application_status),
            $application->room_allocation ?? '-',
            $application->package ?? '-',
            $application->payment_exception ? 'Yes' : 'No',
            $application->num_participants ?? 1,
            $application->processed_by ?? '-',
            optional($application->processed_at)->format('Y-m-d H:i:s') ?? '-',
            optional($application->created_at)->format('Y-m-d H:i:s') ?? '-',
        ];
    }
}
