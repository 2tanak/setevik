<?php

namespace App\Console\Commands\Requisition;

use App\User;

use Carbon\Carbon;
use Illuminate\Console\Command;

class Check extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'requisition:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check requisitions for residents activity';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (env('CRON_REQUISITION_CHECK', false)) {
            $users = User::where('id', '>', 1)->get();
            foreach ($users as $user) {
                $subscriptions = $user->subscriptions()
                    ->where('started_at', '<=', Carbon::now())
                    ->where('expired_at', '>', Carbon::now());

                if ((int) $subscriptions->count() == 0) {
                    $user->update(['has_activity_oss' => false]);
                } else {
                    $user->update(['has_activity_oss' => true]);
                }
            }
        } else {
            \Log::info('Turned off: '.__METHOD__);
        }
    }
}
