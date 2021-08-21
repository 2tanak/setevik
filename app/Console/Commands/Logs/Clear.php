<?php

namespace App\Console\Commands\Logs;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class Clear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear log file';

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
            file_put_contents($logFile, '');
            $this->info("Log file cleared!");
        } else {
            echo sprintf('Log file not found %s%s', $logFile, PHP_EOL);
        }
    }
}
