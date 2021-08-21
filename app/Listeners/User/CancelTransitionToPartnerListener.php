<?php

namespace App\Listeners\User;

use App\Role;
use App\Models\News;
use App\Models\Promo;
use App\Models\Event;
use App\Models\Cabinet;
use App\Models\Package;
use App\Models\Documents;
use App\Models\BePartnerRequest;
use App\Services\Sib\BinaryTreeService;
use App\Services\Sib\BonusBinaryService;
use App\Events\User\CancelTransitionToPartner;

use Carbon\Carbon;

class CancelTransitionToPartnerListener
{
    protected $binaryTreeService;
    protected $bonusBinaryService;

    public function __construct(BinaryTreeService $binaryTreeService, BonusBinaryService $bonusBinaryService)
    {
        $this->binaryTreeService = $binaryTreeService;
        $this->bonusBinaryService = $bonusBinaryService;
    }

    public function handle(CancelTransitionToPartner $event)
    {
        $activation = $event->bePartnerRequest;
        $user       = $activation->user;

        // mark 'has_activity_sib' manually
        $user->has_activity_sib = false;

        //
        $user->is_oss_ever          = true;
        $user->sib_registered_at    = NULL;
        $user->activated_at         = NULL;
        $user->save();
    }
}
