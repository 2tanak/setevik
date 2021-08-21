<?php

namespace App\Listeners\User;

use App\Role;
use App\Models\News;
use App\Models\Promo;
use App\Models\Event;
use App\Models\OssNews;
use App\Models\Documents;
use App\Events\User\PartnerActivated;
use App\Services\Sib\BinaryTreeService;
use App\Services\Sib\BonusBinaryService;
use App\Services\Oss\TreeService as OssTreeService;
use App\Services\Sib\WalletService as SibWalletService;

class PartnerActivatedListener
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

    public function handle(PartnerActivated $event)
    {
        $user       = $event->user;
        $roles      = Role::where('slug', 'partner')->get();

        // binary tree placement
        $this->binaryTreeService->place($user);

        // binary bonus enrolment
        $this->bonusBinaryService->activate($user);

        //
        // land to OSS tree
        (new OssTreeService())->bindUserToCurator($user, $user->getCuratorPartner());

        // generate wallet records
        $this->walletService->generateWallet($user);

        // bind new role
        $user->roles()->sync($roles);


        //
        $newsLast = News::where('is_active', true)
            ->where('created_at', '<=', $user->activated_at)
            ->get()
            ->last();

/*
        foreach (News::get() as $item) {
            //$item->watchedBy($user);
        }
*/
        if ($newsLast) {
            $newsLast->attachBadgeFor($user);
        }


        //
        $docLast = Documents::where('is_active', true)
            ->where('created_at', '<=', $user->activated_at)
            ->get()
            ->last();

/*
        foreach (Documents::get() as $item) {
           $item->watchedBy($user);
        }
*/
        if ($docLast) {
            $docLast->attachBadgeFor($user);
        }


        //
        $eventLast = Event::where('is_active', true)
            ->where('created_at', '<=', $user->activated_at)
            ->get()
            ->last();

/*
        foreach (Event::get() as $item) {
            $item->watchedBy($user);
        }
*/
        if ($eventLast) {
            $eventLast->attachBadgeFor($user);
        }


        //
        $promoLast = Promo::where('is_active', true)
            ->where('created_at', '<=', $user->activated_at)
            ->get()
            ->last();

/*
        foreach (Promo::get() as $item) {
           $item->watchedBy($user);
        }
*/
        if ($promoLast) {
            $promoLast->attachBadgeFor($user);
        }


        //
        $ossNewsLast = OssNews::where('is_active', true)
            ->where('created_at', '<=', $user->activated_at)
            ->get()
            ->last();

/*
        foreach (OssNews::get() as $item) {
            $item->watchedBy($user);
        }
*/
        if ($ossNewsLast) {
            $ossNewsLast->attachBadgeFor($user);
        }
    }
}
