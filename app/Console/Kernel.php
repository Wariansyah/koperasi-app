<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Helpers\helper;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */


    protected function schedule(Schedule $schedule)
    {
<<<<<<< HEAD
        // Panggil helper function generateDailyKas setiap hari pada pukul 00:01
        $schedule->call(function () {
            generateDailyKas();
        })->dailyAt('00:01');
        // $schedule->command('kas:update')->daily();
=======

>>>>>>> 6da32b511d17466018660ab4b30b15317a01b628
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
