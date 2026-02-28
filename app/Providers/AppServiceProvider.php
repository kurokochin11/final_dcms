<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; 
use App\Models\Appointment;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void { }

    public function boot(): void
    {
        // View Composer to share notifications globally
        View::composer('navigation-menu', function ($view) {
            $notifications = Appointment::with('patient')
                ->whereDate('appointment_date', now()->format('Y-m-d'))
                ->where('status', 'Scheduled')
                ->get();

            $view->with('todayScheduledAppointments', $notifications);
        });
    }
}