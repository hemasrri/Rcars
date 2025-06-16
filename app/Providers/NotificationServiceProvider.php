<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $notifications = $user->notifications;
                $unreadCount = $user->unreadNotifications->count();
                $view->with(compact('notifications', 'unreadCount'));
            }
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}
