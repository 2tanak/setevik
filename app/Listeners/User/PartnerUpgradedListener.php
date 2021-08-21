<?php

namespace App\Listeners\User;

use App\Events\User\PartnerUpgraded;
use App\Services\Sib\BinaryTreeService;
use App\Services\Sib\BonusBinaryService;

class PartnerUpgradedListener
{
    protected $binaryTreeService;
    protected $bonusBinaryService;

    public function __construct(BinaryTreeService $binaryTreeService, BonusBinaryService $bonusBinaryService)
    {
        $this->binaryTreeService = $binaryTreeService;
        $this->bonusBinaryService = $bonusBinaryService;
    }

    public function handle(PartnerUpgraded $event)
    {
        // binary tree upgrade
        $this->binaryTreeService->upgrade($event->user);

        // binary bonus upgrading
        $this->bonusBinaryService->upgrade($event->user);
    }
}
