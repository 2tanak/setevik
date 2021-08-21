<?php

namespace App\Observers;

use App\Models\BePartnerRequest;
use App\Events\User\ResidentBePartnerRequested;
use App\Events\User\ResidentTransitionToPartner;

use Carbon\Carbon;

class BePartnerRequestObserver
{
    public function creating(BePartnerRequest $model)
    {
        if ($model->isDirty('link')) {
            event(new ResidentBePartnerRequested($model->user));
        }
    }

    public function updating(BePartnerRequest $model)
    {
        if ($model->isDirty('link')) {
            event(new ResidentBePartnerRequested($model->user));
        }

        if ($model->isDirty('is_confirmed')) {
            $model->confirmed_at = Carbon::now();

            event(new ResidentTransitionToPartner($model));
        }
    }
}