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
         $schedule->command('update:kas_awal')->dailyAt('11:30'); // Ubah waktu sesuai kebutuhan
     }
     

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected $commands = [
        \App\Console\Commands\UpdateKasAwalForNextDay::class,
    ];
}
