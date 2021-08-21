<?php

namespace App\Observers;

use App\Models\OssNews;
use App\Events\News\Registered;
use App\Events\Requisitions\ConfirmedByOwner;
use App\Events\Requisitions\ConfirmedByAdmin;
use App\Events\Requisitions\ConfirmedByCurator;

use Carbon\Carbon;

class NewsObserver
{
    public function creating(OssNews $news)
    {
		
        event(new Registered($news));
    }

}