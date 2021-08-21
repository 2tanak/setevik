<?php

namespace App\Observers;

use App\User;
use App\Models\Wallet;
use App\Services\Sib\WalletService as SibWalletService;

class WalletObserver
{
    protected $walletService;

    public function __construct(SibWalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function creating(Wallet $wallet)
    {
        //
    }

    public function updating(Wallet $wallet)
    {
        //
    }
}