<?php

namespace App\Observers;

use App\User;
use App\Models\Sale;
use App\Services\Sib\WalletService as SibWalletService;

class SaleObserver
{
    protected $walletService;

    public function __construct(SibWalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function creating(Sale $sale)
    {
        $this->walletService->enrol($sale);
    }
}