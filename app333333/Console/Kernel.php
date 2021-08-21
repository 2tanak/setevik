<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // logs
        Commands\Logs\Open::class,
        Commands\Logs\Clear::class,

        // data
        Commands\Data\Reset::class,
        Commands\Data\Import::class,

        // avatars
        Commands\Avatar\Import::class,

        // requisitions
        Commands\Requisition\Check::class,

        // wallets
        Commands\WalletsUpdater::class,

         // wallets cash migration - expected ---> available
        Commands\AvailableUpdater::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->command('requisition:check')
            ->withoutOverlapping();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
