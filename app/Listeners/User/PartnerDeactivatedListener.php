<?php

namespace App\Listeners\User;

use App\Events\User\PartnerDeactivated;
use App\Services\Sib\BinaryTreeService;
use App\Services\Sib\BonusBinaryService;

/**
 * @deprecated
 *
 * @package App\Listeners\User
 */
class PartnerDeactivatedListener
{
    protected $binaryTreeService;
    protected $bonusBinaryService;

    public function __construct(BinaryTreeService $binaryTreeService, BonusBinaryService $bonusBinaryService)
    {
        $this->binaryTreeService = $binaryTreeService;
        $this->bonusBinaryService = $bonusBinaryService;
    }

    public function handle(PartnerDeactivated $event)
    {
        $user = $event->user;

        // change avatar
        $user->photo = '/img/avatars/returner.png';

        // change activity
        $user->has_activity_sib = false;

        // binary tree
        $this->binaryTreeService->deactivate($user);

        // binary bonus deactivation
        $this->bonusBinaryService->deactivate($user);
    }
}
