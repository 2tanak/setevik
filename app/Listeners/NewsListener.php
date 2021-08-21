<?php

namespace App\Listeners;

use App\User;

use App\Services\Oss\ProductService;
use App\Models\Trees\OnlineSmartSystemTreeNode;
use App\Services\Oss\TreeService as OssTreeService;
use App\Events\Requisitions\ConfirmedByOwner;
use App\Events\Requisitions\ConfirmedByCurator;
use App\Events\Requisitions\ConfirmedByAdmin;
use App\Events\News\Registered;

use Carbon\Carbon;

class NewsListener
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
				}
		
		
		//$requisition = $event->requisition;
	         //$event->news->attachBadgeFor(User::all());

	 
     /*
	if ($event instanceof Registered) {
            $this->registered($event);
        } 
		
		
		elseif ($event instanceof ConfirmedByOwner) {
            $this->confirmedByOwner($event);
        } elseif ($event instanceof ConfirmedByCurator) {
            $this->confirmedByCurator($event);
        } elseif ($event instanceof ConfirmedByAdmin) {
            $this->confirmedByAdmin($event);
        }
		*/
    }
 public function registered(Registered $event)
    {

        //$requisition = $event->news;
        //dd($requisition);
        //$requisition->attachBadgeFor(User::all());
        $filename = 'datatest.json';
        $data[] = 1;
        $fp = fopen($filename, 'a');
        fwrite($fp, json_encode($data));
        fclose($fp);
    }
    /**
     * New requisition created
     *
     * @param Registered $event
     */

   

  
    
}