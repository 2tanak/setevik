<?php

namespace App\Listeners\User;

use App\Events\User\PartnerBlocked;
use App\Services\Sib\BinaryTreeService;
use App\Services\Sib\BonusBinaryService;

class PartnerBlockedListener
{
    protected $binaryTreeService;
    protected $bonusBinaryService;

    public function __construct(BinaryTreeService $binaryTreeService, BonusBinaryService $bonusBinaryService)
    {
        $this->binaryTreeService = $binaryTreeService;
        $this->bonusBinaryService = $bonusBinaryService;
    }

    public function handle(PartnerBlocked $event)
    {
        //
    }
}
