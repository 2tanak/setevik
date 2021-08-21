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
use App\Events\User\ResidentTransitionToPartner;

use Carbon\Carbon;

class ResidentTransitionToPartnerListener
{
    protected $binaryTreeService;
    protected $bonusBinaryService;

    public function __construct(BinaryTreeService $binaryTreeService, BonusBinaryService $bonusBinaryService)
    {
        $this->binaryTreeService = $binaryTreeService;
        $this->bonusBinaryService = $bonusBinaryService;
    }

    public function handle(ResidentTransitionToPartner $event)
    {
        $activation = $event->bePartnerRequest;
        $user       = $activation->user;
        $package    = Package::findOrFail($activation->package_id);
        $cabinet    = Cabinet::where('code', 'sib')->first();
        $role       = Role::where('slug', 'partner')->first();

        // mark 'has_activity_sib' manually
        $user->has_activity_sib = true;

        //
        $user->is_oss_ever          = true;
        $user->sib_registered_at    = Carbon::now();
        $user->activated_at         = Carbon::now();

        $user->cabinet()->associate($cabinet);
        $user->package()->associate($package);
        $user->roles()->sync($role);
        $user->save();

        // binary tree placement
        $this->binaryTreeService->place($user);

        // binary bonus enrolment
        $this->bonusBinaryService->activate($user);

        //
        $newsLast = News::where('is_active', true)
            ->where('created_at', '<=', $user->activated_at)
            ->get()
            ->last();

        foreach (News::get() as $item) {
			if($newsLast->id != $item->id){

            $item->watchedBy($user);
						}
        }

        if ($newsLast) {
            $newsLast->attachBadgeFor($user);
        }


        //
        $docLast = Documents::where('is_active', true)
            ->where('created_at', '<=', $user->activated_at)
            ->get()
            ->last();

        foreach (Documents::get() as $item) {
			if($docLast->id != $item->id){

            $item->watchedBy($user);
						}
        }

        if ($docLast) {
            $docLast->attachBadgeFor($user);
        }


        //
        $eventLast = Event::where('is_active', true)
            ->where('created_at', '<=', $user->activated_at)
            ->get()
            ->last();

        foreach (Event::get() as $item) {
			if($eventLast->id != $item->id){
				$item->watchedBy($user);
				}
        }

        if ($eventLast) {
            $eventLast->attachBadgeFor($user);
        }


        //
        $promoLast = Promo::where('is_active', true)
            ->where('created_at', '<=', $user->activated_at)
            ->get()
            ->last();

        foreach (Promo::get() as $item) {
			if($promoLast->id != $item->id){

            $item->watchedBy($user);
						}
        }

        if ($promoLast) {
            $promoLast->attachBadgeFor($user);
        }
    }
}
