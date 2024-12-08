<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Jadwalkan command yang telah kita buat
        $schedule->command('send:lpj-reminder-emails')->everyThreeDays();

        // Jadwalkan command untuk mengirim Approval Reminder setiap hari
        $schedule->command('app:send-approval-reminders')->dailyAt('10:00');
    }

    protected $middlewareGroups = [
        'web' => [
            // Middleware lainnya
            \App\Http\Middleware\CheckApprovalReminder::class,
        ],
    ];


    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
