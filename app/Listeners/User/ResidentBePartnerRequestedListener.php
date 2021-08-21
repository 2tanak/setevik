<?php

namespace App\Listeners\User;

use App\User;
use App\Events\User\ResidentBePartnerRequested;

class ResidentBePartnerRequestedListener
{
    public function handle(ResidentBePartnerRequested $event)
    {
        $bePartnerRequest = $event->user->bePartnerRequest;

        if ($bePartnerRequest) {
            $bePartnerRequest->attachBadgeFor(User::find(1));
        }
    }
}
