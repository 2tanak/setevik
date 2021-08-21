<?php

namespace App\Console\Commands\Logs;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class Open extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:open';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Open log file by default Application of OS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $logFile = sprintf('%s/logs/laravel.log', storage_path());

        if (file_exists($logFile)) {
            shell_exec("open $logFile");
        } else {
            echo sprintf('Log file not found %s%s', $logFile, PHP_EOL);
        }
    }
}
