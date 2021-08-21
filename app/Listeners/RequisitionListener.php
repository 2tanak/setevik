<?php

namespace App\Listeners;

use App\User;
use App\Models\Sale;
use App\Models\Subscription;
use App\Services\Oss\ProductService;
use App\Models\Trees\OnlineSmartSystemTreeNode;
use App\Services\Oss\TreeService as OssTreeService;
use App\Events\Requisitions\Registered;
use App\Events\Requisitions\ConfirmedByOwner;
use App\Events\Requisitions\ConfirmedByCurator;
use App\Events\Requisitions\ConfirmedByAdmin;
use App\Events\Requisitions\CanceledByCurator;
use App\Events\Requisitions\CanceledByAdmin;

use Carbon\Carbon;

class RequisitionListener
{
    protected $productService;
    protected $ossTreeService;

    public function __construct(ProductService $productService, OssTreeService $ossTreeService)
    {
        $this->productService = $productService;
        $this->ossTreeService = $ossTreeService;
    }

    public function handle($event)
    {
        if ($event instanceof Registered) {
            $this->registered($event);
        } elseif ($event instanceof ConfirmedByOwner) {
            $this->confirmedByOwner($event);
        } elseif ($event instanceof ConfirmedByCurator) {
            $this->confirmedByCurator($event);
        } elseif ($event instanceof CanceledByCurator) {
            $this->canceledByCurator($event);
        } elseif ($event instanceof ConfirmedByAdmin) {
            $this->confirmedByAdmin($event);
        } elseif ($event instanceof CanceledByAdmin) {
            $this->canceledByAdmin($event);
        }
    }

    /**
     * New requisition created
     *
     * @param Registered $event
     */
    public function registered(Registered $event)
    {
        //
    }

    /**
     * Requisition owner attached a file
     *
     * @param ConfirmedByOwner $event
     */
    public function confirmedByOwner(ConfirmedByOwner $event)
    {
		
        $requisition = $event->requisition;

        // attache badge
        $requisition->attachBadgeFor($requisition->curator);
    }

    /**
     * Owner's curator attached a file
     *
     * @param ConfirmedByCurator $event
     */
    public function confirmedByCurator(ConfirmedByCurator $event)
    {
		
        $requisition = $event->requisition;

        // detach badge
        $requisition->unmarkBadgeFor($requisition->curator);

        // attache badge for admin
        $requisition->attachBadgeFor(User::find(1), ['/admin/requisitions']);
    }

    /**
     * Requisition canceled by curator
     *
     * @param CanceledByCurator $event
     */
    public function canceledByCurator(CanceledByCurator $event)
    {
        $requisition = $event->requisition;

        // detach badge
        $requisition->unmarkBadgeFor($requisition->curator);
    }

    /**
     * Requisition confirmed by administrator
     *
     * @param ConfirmedByAdmin $event
     */
    public function confirmedByAdmin(ConfirmedByAdmin $event)
    {
		
        $requisition    = $event->requisition;
        $curator        = $requisition->curator;
        $user           = $requisition->user;
        $product        = $requisition->product;

        // we have to activate user if the user never been activated before
        if (!$user->is_active) {
            $user->is_active = true;
        }

        // subscribe to the product
		
		   if($this->productService->checkHasSubscription($product, $user)) {
              $price = $this->productService->getPriceWithDiscount($product, $user) / 2;
		   }else{
              $hours = $requisition->created_at->diffInHours($user->registration->created_at);
              if($hours <= 24){$price = 15;}
              if($hours > 24){$price = 45;}

		   }
		
        Subscription::create([
            'user_id'       => $user->id,
            'product_id'    => $product->id,
        ]);

        // assigning activity
        $user->has_activity_oss = true;
        $user->save();
	 

		

        // create sale
        Sale::create([
            'seller_id'     => $curator->id,
            'customer_id'   => $user->id,
            'product_id'    => $product->id,
			'price'=>$price,

            //'price'         => $this->productService->getPriceWithDiscount($product, $user) / 2,

            'description'   => $requisition->requisitionType->name,
        ]);

        //
        if ($user->isResident()) {

            //
            if (OnlineSmartSystemTreeNode::where('user_id', $user->id)->count() == 0) {
                // land to OSS tree
                $this->ossTreeService->bindUserToCurator($user, $curator);
            }

        } elseif ($user->isPartner() && $user->is_oss_ever == false) {

            $user->is_oss_ever = true;

            if (!$user->oss_registered_at) {
                $user->oss_registered_at = Carbon::now();
            }

            if (!$user->oss_activated_at) {
                $user->oss_activated_at = Carbon::now();
            }

            $user->save();

        }

        $requisition->detachBadgeFromMenu(User::find(1), '/admin/requisitions');
    }

    /**
     * Requisition canceled by administrator
     *
     * @param CanceledByAdmin $event
     */
    public function canceledByAdmin(CanceledByAdmin $event)
    {

        $requisition    = $event->requisition;
        $requisition->detachBadgeFromMenu(User::find(1), '/admin/requisitions');
    }
}