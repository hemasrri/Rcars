<?php

namespace App\Exports;

use App\Models\Payment; 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
class SemesterPaymentExport implements FromCollection, WithHeadings, WithMapping
{
    protected $session;
    protected $semester;

    public function __construct($session, $semester)
    {
        $this->session = $session;
        $this->semester = $semester;
    }

    public function collection()
    {
        return Payment::where('session', $this->session)
            ->where('semester', $this->semester)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Session',
            'Semester',
            'Payment ID',
            'Application ID',
            'Amount (RM)',
            'Status',
            'Payment Date',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->session,
            $payment->semester,
            $payment->payment_id,
            $payment->application_id,
            number_format($payment->amount, 2),
            ucfirst($payment->payment_status),
            optional($payment->payment_date)->format('Y-m-d') ?? '-',
        ];
    }
}