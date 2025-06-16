<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
  public function boot()
{
    View::composer('*', function ($view) {
        // For regular users
        if (Auth::check()) {
            $user = Auth::user();
            $notifications = $user->notifications;
            $unreadCount = $user->unreadNotifications->count();
            $view->with([
                'notifications' => $notifications,
                'unreadCount' => $unreadCount
            ]);
        }

        // For admins
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            $adminNotifications = $admin->notifications;
            $adminUnreadCount = $admin->unreadNotifications->count();
            $view->with([
                'adminNotifications' => $adminNotifications,
                'adminUnreadCount' => $adminUnreadCount
            ]);
        }
    });
}
}
