<?php

namespace App\Console\Commands\Data;

use App\Models\File;
use App\Models\LearnVideo;
use App\Models\BroadcastVideo;

use Illuminate\Console\Command;

class Reset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:reset {--log} {--hard}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all data except the journal';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = [];
        $models = [
//            Journal::class,
            File::class,
            BroadcastVideo::class,
            LearnVideo::class,
        ];
        foreach ($models as $model) {
            $data[$model] = $model::all();
        }

        $list = [
            [
                'type' => 'command',
                'args' => 'migrate:refresh'
            ],
            [
                'type' => 'command',
                'args' => 'db:seed'
            ],
        ];

        if ($this->option('log')) {
            $list[] = [
                'type' => 'command',
                'args' => 'log:clear'
            ];
        }

        $bar = $this->output->createProgressBar(count($list));

        foreach ($list as $item) {
            switch ($item['type']) {
                case 'command':
                    $this->callSilent($item['args']);
                    break;
            }
            $bar->advance();
        }

        //
        if ($this->option('hard') == false) {
            foreach ($data as $class => $items) {
                $class::insert($items->toArray());
            }
        }

        $bar->finish();
        $this->info("\n");
    }
}
