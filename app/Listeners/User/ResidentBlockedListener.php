<?php

namespace App\Listeners\User;

use App\Events\User\ResidentBlocked;
use App\Services\Sib\BinaryTreeService;
use App\Services\Sib\BonusBinaryService;

class ResidentBlockedListener
{
    protected $binaryTreeService;
    protected $bonusBinaryService;

    public function __construct(BinaryTreeService $binaryTreeService, BonusBinaryService $bonusBinaryService)
    {
        $this->binaryTreeService = $binaryTreeService;
        $this->bonusBinaryService = $bonusBinaryService;
    }

    public function handle(ResidentBlocked $event)
    {
        //
    }
}
