<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentsExport implements FromCollection, WithHeadings
{
    protected $payments;

    public function __construct(Collection $payments)
    {
        $this->payments = $payments;
    }

    public function collection()
    {
        return $this->payments->map(function ($payment) {
            return [
                'payment_id'       => $payment->payment_id,
                'application_id'   => $payment->application_id,
                'amount'           => $payment->amount,
                'payment_status'   => $payment->payment_status,
                'payment_date'     => $payment->payment_date,
                'session'          => $payment->session,
                'semester'         => $payment->semester,
                'payment_datetime' => $payment->payment_datetime,
                'payment_method'   => $payment->payment_method,
                'is_exception'     => $payment->is_exception,
                'transaction_id'   => $payment->transaction_id,
                'created_at'       => $payment->created_at,
                'updated_at'       => $payment->updated_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Payment ID',
            'Application ID',
            'Amount',
            'Payment Status',
            'Payment Date',
            'Session',
            'Semester',
            'Payment DateTime',
            'Payment Method',
            'Is Exception',
            'Transaction ID',
            'Created At',
            'Updated At',
        ];
    }
}
