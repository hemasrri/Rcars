<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class CleanUpPayments extends Command
{
    protected $signature = 'payments:cleanup';

    protected $description = 'Remove pending payments if a successful payment exists for the same application';

    public function handle()
    {
        $this->info('Starting cleanup of payments...');

        // Find all application_ids which have at least one successful payment
        $applicationIds = Payment::where('payment_status', 'successful')
            ->distinct()
            ->pluck('application_id');

        $deletedCount = 0;

        foreach ($applicationIds as $applicationId) {
            // Delete pending payments for this application_id
            $deleted = Payment::where('application_id', $applicationId)
                ->where('payment_status', 'pending')
                ->delete();

            $deletedCount += $deleted;
        }

        $this->info("Cleanup complete. Deleted {$deletedCount} pending payment(s).");
        return 0;
    }
}
