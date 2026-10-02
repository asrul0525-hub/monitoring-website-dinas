<?php

namespace App\Console;

use App\Http\Controllers\DashboardController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Jalankan pengecekan keaktifan seluruh website OPD setiap 15 menit secara otomatis
        $schedule->call(function () {
            app(DashboardController::class)->checkAllStatus();
        })->everyFifteenMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}