<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\HostelController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserApplicationController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AjaxController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Default login route
Route::get('/', function () {
    return view('auth.login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Dashboard (no email verification required)
Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware('auth:admin')
    ->name('admin.dashboard');

    Route::post('/admin/notifications/mark-all-read', [AdminController::class, 'markAllRead'])
    ->name('admin.notifications.markAllRead')
    ->middleware('auth:admin');
    Route::put('/admin/user/{staff_id}/update', [AdminController::class, 'update'])->name('user.update');
Route::put('/admin/user/{staff_id}/change-password', [AdminController::class, 'changePassword'])->name('user.changePassword');
Route::delete('/admin/user/{staff_id}', [AdminController::class, 'destroy'])->name('user.delete');


 Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');
    Route::get('/reports/export-applications', [AdminReportController::class, 'exportApplications'])->name('reports.export.applications');
    Route::get('/reports/export-payments', [AdminReportController::class, 'exportPayments'])->name('reports.export.payments');
    // User management route
    Route::get('/admin/user-management', [AdminController::class, 'userManagement'])->name('admin.user-management');
    Route::post('/admin/user', [AdminController::class, 'store'])->name('user.store');
    Route::post('/admin/users/update-email/{id}', [AdminController::class, 'updateEmail'])->name('user.update-email');

    Route::delete('/admin/user/{id}', [AdminController::class, 'destroy'])->name('user.delete');
    Route::post('/admin/change-password/{id}', [AdminController::class, 'changePassword'])->name('user.change-password');
    //Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    //Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
    //Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

// User Dashboard (UTHM and non-UTHM)
Route::middleware('auth:web')->group(function () {
    Route::get('/users/dashboard', function () {
        if (auth()->user()->user_type === 'uthm') {
            return view('users.dashboard');
        }

        if (auth()->user()->user_type === 'non-uthm' && is_null(auth()->user()->email_verified_at)) {
            return redirect()->route('verification.notice');
        }

        return view('users.dashboard');
    })->name('users.dashboard');

    Route::middleware('auth:web')->group(function () {
    Route::get('/users/dashboard', [UserController::class, 'index'])->name('users.dashboard');
    Route::get('/users//payment-history', [PaymentController::class, 'history'])->name('payments.history');
    Route::get('/users//account', [UserController::class, 'account'])->name('users.account');
    Route::put('/users/{id}/update', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{id}/change-password', [UserController::class, 'changePassword'])->name('users.changePassword');
   //Route::get('/users//faq', [PageController::class, 'faq'])->name('faq');
Route::get('/payments/{payment}/download', [PaymentController::class, 'downloadReceipt'])->name('payments.download');
Route::get('/receipt/download/{paymentId}', [PaymentController::class, 'downloadReceipt'])
    ->name('payments.download.receipt');
Route::get('/payment/success/{payment_id}', [PaymentController::class, 'showSuccessPage'])->name('payment.success');
Route::delete('/application/{id}', [ApplicationController::class, 'destroy'])->name('application.destroy');
Route::get('/user/quotation/{application_id}', [UserController::class, 'printQuotation'])->name('user.quotation');

Route::post('/user/notifications/mark-all-read', [UserNotificationController::class, 'markAllRead'])
    ->name('user.notifications.markAllRead')
    ->middleware('auth');

   // Show application form
    Route::get('/application', [UserApplicationController::class, 'showApplicationForm'])->name('application.form');

    // Submit or save application draft
    Route::post('/application/submit', [UserApplicationController::class, 'submitApplication'])->name('application.submit');

    // Fetch packages by category (AJAX)
    Route::get('/application/packages', [UserApplicationController::class, 'fetchPackages'])->name('application.fetchPackages');

    // Get semester and session by date (AJAX)
    Route::get('/application/semester-session', [UserApplicationController::class, 'getSemesterSession'])->name('application.getSemesterSession');

    // Edit existing application form
    Route::get('/application/{application_id}/edit', [UserApplicationController::class, 'edit'])->name('application.edit');

    // Update existing application
    Route::put('/application/{application_id}', [UserApplicationController::class, 'update'])->name('application.update');
    
    Route::get('/application/view/{id}', [UserApplicationController::class, 'show'])->name('application.view');


    // Download application PDF
    Route::get('/application/{id}/download', [UserApplicationController::class, 'downloadPdf'])->name('application.downloadPdf');

Route::post('/fetch-packages', [UserApplicationController::class, 'fetchPackages'])->name('fetch.packages');
Route::post('/get-semester-session', [UserApplicationController::class, 'getSemesterSession'])->name('get.semester_session');
Route::post('/non-uthm-user/autosave', [UserApplicationController::class, 'autoSave'])->name('non-uthm-user.autosave');

// ✅ Payment page for users (UTHM and non-UTHM)
    Route::get('/user/payment/{application}', [PaymentController::class, 'showPaymentPage'])->name('payments.create');
});

    // Email verification notice page
    Route::get('/email/verify', function () {
        if (auth()->user()->user_type === 'uthm') {
            return redirect()->route('users.dashboard');
        }
        return view('auth.verify-email');
    })->name('verification.notice');

    // Resend verification email
    Route::post('/email/verification-notification', function (Request $request) {
        if (auth()->user()->user_type === 'non-uthm') {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('message', 'Verification link sent!');
        }
        return back();
    })->middleware('throttle:6,1')->name('verification.send');
});

// ✅ Email verification callback (no auth middleware required!)
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed']) // DO NOT include 'auth' here
    ->name('verification.verify');

// Register Route (Non-UTHM users)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Forgot Password Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Reset Password Routes
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/applications', [ApplicationController::class, 'index'])->name('admin.applications.index');
    Route::get('/admin/applications/application', [ApplicationController::class, 'index'])->name('admin.applications.view');


    Route::post('/admin/applications/store', [ApplicationController::class, 'store'])->name('admin.applications.store');
    Route::post('/admin/applications/{application}/approve', [ApplicationController::class, 'approve'])->name('admin.applications.approve');
    Route::post('/admin/applications/{application}/reject', [ApplicationController::class, 'reject'])->name('admin.applications.reject');

    Route::get('/admin/applications/download', [ApplicationController::class, 'download'])->name('admin.applications.download');
    Route::get('/admin/applications/{application_id}/edit', [ApplicationController::class, 'edit'])->name('admin.applications.edit');
    Route::put('/admin/applications/{application}', [ApplicationController::class, 'update'])->name('admin.applications.update');
Route::post('/ajax/packages-by-category', [AjaxController::class, 'getPackagesByCategory'])->name('ajax.getPackagesByCategory');
Route::post('/ajax/semester-session', [AjaxController::class, 'getSemesterSession'])->name('ajax.getSemesterSession');
    Route::resource('blocks', controller: BlockController::class)->parameters(['blocks' => 'block_id']);
    Route::resource('rooms', controller: RoomController::class)->parameters(['rooms' => 'room']);
    Route::resource('hostels', HostelController::class)->parameters(['hostels' => 'hostel']);
    Route::resource('packages', PackageController::class)->parameters(['packages' => 'id']);
    Route::resource('semesters', SemesterController::class)->parameters(['semesters' => 'id']);

    // Route for import CSV (POST)
    Route::post('semesters/import', [SemesterController::class, 'import'])->name('semesters.import');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
Route::post('/payment/charge', [PaymentController::class, 'charge'])->name('payments.charge');

    // Export routes
    Route::get('payments/export/excel', [PaymentController::class, 'exportExcel'])->name('payments.export.excel');
    Route::get('payments/export/pdf', [PaymentController::class, 'exportPDF'])->name('payments.export.pdf');
Route::put('/users/{user}/update', [UserController::class, 'update'])->name('users.update');
    Route::get('/user/payment/{id}', [PaymentController::class, 'showPaymentPage'])->name('payments.create');
    
Route::post('/get-semester-session', [SemesterController::class, 'getSemesterSession'])
    ->name('get.semester_session');
});

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');
    
    Route::get('/export-applications/{session}/{semester}', [AdminReportController::class, 'exportApplications'])
         ->name('export.applications');

    Route::get('/export-payments/{session}/{semester}', [AdminReportController::class, 'exportPayments'])
         ->name('export.payments');
         

});


