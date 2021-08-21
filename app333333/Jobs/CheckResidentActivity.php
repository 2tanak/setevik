<?php

namespace App\Jobs;

use App\User;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckResidentActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscriptions = $this->user->subscriptions()
            ->where('started_at', '<=', Carbon::now())
            ->where('expired_at', '>', Carbon::now());

        if ((int) $subscriptions->count() == 0) {
            $this->user->update(['has_activity_oss' => false]);
        }
    }
}
