<?php

namespace App\Observers;

use App\User;
use App\Events\User\PartnerActivated;
use App\Events\User\PartnerUpgraded;
use App\Events\User\PartnerDeactivated;
use App\Events\User\ResidentActivated;
use App\Events\User\ResidentBlocked;

use Carbon\Carbon;

class UserObserver
{
    public function updating(User $user)
    {
        if ($user->isPartner()) {

            // activation/deactivation
            if ($user->isDirty('is_active')) {

                if ($user->is_active && !$user->activated_at) {
                    $user->activated_at = Carbon::now();
                    event(new PartnerActivated($user));
                } elseif (!$user->is_active) {
                    $user->is_blocked = true;
                    event(new PartnerDeactivated($user));
                }
            }

            // Package activating/upgrading detection
            if ($user->isDirty('package_id')) {

                if ((int) $user->getOriginal('package_id') == 0) {
                    //
                } else {

                    // downgrading not allowed
                    if ($user->getOriginal('package_id') > $user->package_id) {
                        \Log::error('Downgrading not allowed for '.$user->email);
                        return false;
                    }

                    event(new PartnerUpgraded($user));
                }
            }

        } elseif ($user->isResident()) {


            
            if ($user->is_active && !$user->oss_activated_at) {

                $user->oss_activated_at = Carbon::now();
                event(new ResidentActivated($user));
            } elseif ($user->is_blocked) {
                event(new ResidentBlocked($user));
            }

        } else {

            //

        }
    }
}