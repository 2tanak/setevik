<?php

namespace App\Listeners\User;

use App\Events\User\PartnerRegistered;
use App\Events\User\ResidentRegistered;

use Illuminate\Auth\Events\Registered;

class RegisteredListener
{
    public function handle(Registered $event)
    {
        $user = $event->user;

        if ($user->isPartner()) {
            event(new PartnerRegistered($user));
        } elseif ($user->isResident()) {
            event(new ResidentRegistered($user));
        } else {
            throw new \Exception('Cabinet not found');
        }
    }
}
