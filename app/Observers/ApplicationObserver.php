<?php

namespace App\Observers;

use App\Models\Application;
use App\Models\Admin;
use App\Notifications\NewApplicationReceived;
use Illuminate\Support\Facades\Notification;

class ApplicationObserver
{
    public function created(Application $application)
    {
        $admins = Admin::all();
        Notification::send($admins, new NewApplicationReceived($application));
    }
}
