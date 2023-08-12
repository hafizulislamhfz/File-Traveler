<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\UpdateStatusCommand;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command(UpdateStatusCommand::class)->everyMinute();//upadate UpdateStatusCommand schedule every one minute
    }

    /**
     * Register the commands for the application.
     */

    protected function commands(): void
    {

        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected $commands = [
        \App\Console\Commands\UpdateStatusCommand::class,
    ];
}
