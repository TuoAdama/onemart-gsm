<?php

namespace App\Console;

use App\Models\Personne;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Nette\Utils\Random;

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
        // $schedule->command('inspire')->hourly();
        $schedule->command('transfert:store')
        ->withoutOverlapping()
        ->runInBackground();
        
        $schedule->command('transfert:send')
        ->everyTwoMinutes()
        ->unlessBetween("08:00", "08:10")
        ->unlessBetween("10:00", "10:10")
        ->unlessBetween("12:00", "12:10")
        ->unlessBetween("16:00", "16:10")
        ->unlessBetween("18:00", "18:10")
        ->unlessBetween("20:00", "20:10")
        ->withoutOverlapping()
        ->runInBackground();

        $schedule->command('solde:consultation')
        ->at("8:05")
        ->at("10:05")
        ->at("12:05")
        ->at("16:05")
        ->at("18:05")
        ->at("20:05")
        ->withoutOverlapping()
        ->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
