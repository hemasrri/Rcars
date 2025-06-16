<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    /**
     * Middleware to ensure only authenticated users access this controller.
     */
    public function __construct()
    {
        $this->middleware('auth'); // Applies to UTHM and Non-UTHM users via default guard
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllRead(Request $request)
    {
        $user = auth()->user();

        if ($user) {
            $user->unreadNotifications->markAsRead();
        }

        return back()->with('status', 'All notifications marked as read.');
    }
}
