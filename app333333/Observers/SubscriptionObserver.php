<?php

namespace App\Observers;

use App\Models\Subscription;
use App\Jobs\CheckResidentActivity;

use Carbon\Carbon;

class SubscriptionObserver
{
    public function creating(Subscription $subscription)
    {
        // new subscription or prolongation
        $actual = Subscription::where('user_id', $subscription->user_id)
            ->where('product_id', $subscription->product_id)
            ->where('expired_at', '>', Carbon::now())
            ->orderByDesc('expired_at')
            ->latest()
            ->first();

        if ($actual) {
            $subscription->started_at = $actual->expired_at;
            $subscription->expired_at = $actual->expired_at->addMonth(1);
        } else {
            $subscription->started_at = Carbon::now();
            $subscription->expired_at = Carbon::now()->addMonth(1);
        }

        // dispatch job
        $job = (new CheckResidentActivity($subscription->user))
            ->delay($subscription->expired_at->addSeconds(1));

        dispatch($job);
    }
}