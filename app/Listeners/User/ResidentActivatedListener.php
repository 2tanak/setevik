<?php

namespace App\Listeners\User;

use App\Role;
use App\Models\OssNews;
use App\Events\User\ResidentActivated;
use App\Services\Sib\BinaryTreeService;
use App\Services\Sib\BonusBinaryService;
use App\Services\Sib\WalletService as SibWalletService;

class ResidentActivatedListener
{
    protected $binaryTreeService;
    protected $bonusBinaryService;
    protected $walletService;

    public function __construct(SibWalletService $walletService, BinaryTreeService $binaryTreeService, BonusBinaryService $bonusBinaryService)
    {
        $this->walletService = $walletService;
        $this->binaryTreeService = $binaryTreeService;
        $this->bonusBinaryService = $bonusBinaryService;
    }

    public function handle(ResidentActivated $event)
    {
        $user = $event->user;
        $roles = Role::where('slug', 'resident')->get();

        // generate wallet records
        $this->walletService->generateWallet($user);

        // bind new role
        $user->roles()->sync($roles);

        //
        $ossNewsLast = OssNews::where('is_active', true)
            ->where('created_at', '<=', $user->oss_activated_at)
            ->get()
            ->last();

        foreach (OssNews::get() as $item) {
			if($ossNewsLast->id !=$item->id){
            $item->watchedBy($user);
			}
        }

        if ($ossNewsLast) {
            $ossNewsLast->attachBadgeFor($user);
        }
    }
}
