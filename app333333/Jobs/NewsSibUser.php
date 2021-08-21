<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Role;

class NewsSibUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $news;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($news)
    {
		$this->news = $news;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		$role = Role::where('slug', 'partner')->first();

            foreach ($role->users as $user) {
                $this->news->attachBadgeFor($user);
            }
			
				

        //
    }
}
