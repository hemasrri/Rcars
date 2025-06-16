<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Application;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Charge;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;



class PaymentController extends Controller
{
    /**
     * Generate a unique payment ID (e.g., ABC123).
     */
    private function generatePaymentId(): string
    {
        $letters = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);
        $numbers = substr(str_shuffle('0123456789'), 0, 3);

        return $letters . $numbers;
    }

    /**
     * Display a list of payments with optional filters.
     */
public function index(Request $request)
{
    $payments = Payment::query()
        // Filter by date range
        ->when($request->filled(['start_date', 'end_date']), function ($query) use ($request) {
            $query->whereBetween('payment_datetime', [$request->start_date, $request->end_date]);
        })
        // Filter by payment status
        ->when($request->filled('status'), function ($query) use ($request) {
            $query->where('payment_status', $request->status);
        })
        // Filter by combined session and semester
        ->when($request->filled('session_semester'), function ($query) use ($request) {
            $parts = explode('/', $request->session_semester);
            if (count($parts) === 3) {
                $session = $parts[0] . '/' . $parts[1]; // e.g. "2024/2025"
                $semester = $parts[2];                  // e.g. "2"
                $query->where('session', $session)
                      ->where('semester', $semester);
            }
        })
        ->with(['application', 'semester'])
        ->orderBy($request->get('sort', 'created_at'), $request->get('order', 'desc'))
        ->paginate(10);

    // Semester options for filter
    $semesters = Semester::orderBy('session')->orderBy('semester')->get();
    $semesterOptions = $semesters->mapWithKeys(function ($s) {
        $value = str_replace(' ', '', $s->session) . '/' . $s->semester;
        $label = $s->session . ' Semester ' . $s->semester;
        return [$value => $label];
    });

    $selectedSemester = $request->session_semester;

    return view('admin.payments.payment', compact('payments', 'semesterOptions', 'selectedSemester'));
}


    /**
     * Show a specific payment record.
     */
    public function show(Payment $payment)
    {
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Process Stripe payment and store record in DB.
     */
   public function charge(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:1',
        'stripeToken' => 'required|string',
        'application_id' => 'required|string',
    ]);

    // ✅ Check if successful payment already exists for the application
    $existingPayment = Payment::where('application_id', $request->application_id)
        ->where('payment_status', 'paid')
        ->first();

    if ($existingPayment) {
        return back()->with('error', 'Payment for this application has already been made.');
    }

        $application = Application::findOrFail($request->application_id);

    try {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $charge = Charge::create([
            'amount' => $request->amount * 100,
            'currency' => 'myr',
            'source' => $request->stripeToken,
            'description' => 'Payment for application ID: ' . $request->application_id,
        ]);

           // Check if there's a pending payment first
$pendingPayment = Payment::where('application_id', $application->application_id)
    ->where('payment_status', 'pending')
    ->first();

if ($pendingPayment) {
        Log::info('Found pending payment for application: ' . $application->application_id);

    $pendingPayment->update([
        'payment_id'       => $this->generatePaymentId(),
        'amount'           => $request->amount,
        'transaction_id'   => $charge->id,
        'payment_status'   => 'paid',
        'payment_method'   => 'stripe',
        'payment_date'     => now()->toDateString(),
        'payment_datetime' => now(),
        'semester'         => $application->semester,
        'session'          => $application->session,
    ]);
    $payment = $pendingPayment;

    // ✅ Update application status
    $application->update(['application_status' => 'approved']);
} else {
    $payment = Payment::create([
        'payment_id'       => $this->generatePaymentId(),
        'application_id'   => $application->application_id,
        'amount'           => $request->amount,
        'transaction_id'   => $charge->id,
        'payment_status'   => 'paid',
        'payment_method'   => 'stripe',
        'payment_date'     => now()->toDateString(),
        'payment_datetime' => now(),
        'semester'         => $application->semester,
        'session'          => $application->session,
    ]);

    // ✅ Update application status
    $application->update(['application_status' => 'approved']);
}
// ✅ Notify user after payment
$application->user->notify(new \App\Notifications\PaymentReceived($payment));

return view('users.payment-success', ['payment' => $payment]);
   } catch (\Exception $e) {
        Log::error('Stripe payment error: ' . $e->getMessage());
        return back()->with('error', 'Payment failed: ' . $e->getMessage());
    }
}


    /**
     * Export payments to Excel with optional filters.
     */
public function exportExcel(Request $request) 
{
    $paymentsQuery = Payment::query();

    if ($request->filled('status')) {
        $paymentsQuery->where('payment_status', $request->status);
    }

    if ($request->filled('session')) {
        list($session, $semester) = explode('/', $request->session);
        $paymentsQuery->where('session', $session)
                      ->where('semester', $semester);
    }

    if ($request->filled('start_date')) {
        $paymentsQuery->whereDate('payment_date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $paymentsQuery->whereDate('payment_date', '<=', $request->end_date);
    }

    $payments = $paymentsQuery->with('application.user')->get();

    return Excel::download(new PaymentsExport($payments), 'payments.xlsx');
}


public function exportPDF(Request $request)
{
    $paymentsQuery = Payment::query();

    if ($request->filled('status')) {
        $paymentsQuery->where('payment_status', $request->status);
    }

    if ($request->filled('session')) {
        list($session, $semester) = explode('/', $request->session);
        $paymentsQuery->where('session', $session)
                      ->where('semester', $semester);
    }

    if ($request->filled('start_date')) {
        $paymentsQuery->whereDate('payment_date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $paymentsQuery->whereDate('payment_date', '<=', $request->end_date);
    }

    $payments = $paymentsQuery->with('application.user')->get();

    $pdf = Pdf::loadView('admin.exports.payments-pdf', compact('payments'))
              ->setPaper('a4', 'landscape');

    return $pdf->download('payments.pdf');
}



public function markPaymentSuccess(Request $request)
{
    $request->validate([
        'payment_id' => 'required|string',
        'payment_method' => 'required|in:FPX,Online Banking,Credit Card', // No 'Cash'
        'transaction_id' => 'nullable|string',
        'remarks' => 'nullable|string',
    ]);

    $payment = Payment::where('payment_id', $request->payment_id)->first();

    if (!$payment) {
        return back()->with('error', 'Payment record not found.');
    }

    $payment->payment_status = 'paid';
    $payment->payment_datetime = now();
    $payment->payment_method = $request->payment_method;
    $payment->transaction_id = $request->transaction_id ?? null;
    $payment->remarks = $request->remarks ?? null;
    $payment->verified_by = Auth::guard('admin')->user()->staff_name ?? 'System';
    $payment->save();

    return view('users.payment-success', ['payment' => $payment  // pass payment details
]);

}
public function downloadReceipt($paymentId)
{
    $payment = Payment::with('application.user')->where('payment_id', $paymentId)->firstOrFail();

    // Load Blade view and pass data to generate PDF
    $pdf = Pdf::loadView('users.receipt', compact('payment'));

    // Return file download
    return $pdf->download('receipt_' . $payment->payment_id . '.pdf');
}


public function history()
{
    $user = auth()->user();
    $payments = Payment::where('user_id', $user->id)->get();

    return view('users.payment-history', compact('payments'));
}
public function showSuccessPage($payment_id)
{
    $payment = Payment::with('application')->findOrFail($payment_id);
    return view('users.payment-success', compact('payment'));
}
public function markPaymentException(Request $request)
{
    $request->validate([
        'payment_id' => 'required|string',
        'remarks' => 'nullable|string',
    ]);

    $payment = Payment::where('payment_id', $request->payment_id)->first();

    if (!$payment) {
        return back()->with('error', 'Payment not found.');
    }

    // Update payment record
    $payment->payment_status = 'exception';
    $payment->payment_datetime = now();
    $payment->remarks = $request->remarks ?? 'Marked as exception by admin';
    $payment->verified_by = Auth::guard('admin')->user()->staff_name ?? 'System';
    $payment->save();

    // ✅ Also update the related application
    $application = $payment->application;
    if ($application) {
        $application->payment_exception = 1;
        $application->save();
    }

    return back()->with('success', 'Payment marked as exception and application updated.');
}

     public function showPaymentPage($id)
    {
        // Fetch the application data based on the ID
        $application = Application::find($id); // Assuming you have an Application model

        if (!$application) {
            // Handle the case where the application is not found
            abort(404, 'Application not found');
        }

        // Pass the application data to the view
        return view('users.payment', compact('application'));
    }
    
    

}
