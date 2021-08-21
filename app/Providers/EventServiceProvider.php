<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
//        'App\Events\Event' => [
//            'App\Listeners\EventListener',
//        ],
        'Illuminate\Auth\Events\Registered' => [
            'App\Listeners\User\RegisteredListener',
        ],
        'Illuminate\Mail\Events\MessageSending' => [

        ],

        // партнеры
        'App\Events\User\PartnerActivated' => [
            'App\Listeners\User\PartnerActivatedListener',
            'App\Listeners\JournalListener',
        ],
        'App\Events\User\PartnerBlocked' => [
            'App\Listeners\User\PartnerBlockedListener',
            'App\Listeners\JournalListener',
        ],
        'App\Events\User\PartnerDeactivated' => [
            'App\Listeners\User\PartnerDeactivatedListener',
            'App\Listeners\JournalListener',
        ],
        'App\Events\User\PartnerRegistered' => [
            'App\Listeners\User\PartnerRegisteredListener',
            'App\Listeners\JournalListener',
        ],
        'App\Events\User\PartnerUpgraded' => [
            'App\Listeners\User\PartnerUpgradedListener',
            'App\Listeners\JournalListener',
        ],

        // резиденты
        'App\Events\User\ResidentActivated' => [
            'App\Listeners\User\ResidentActivatedListener',
            'App\Listeners\JournalListener',
        ],
        'App\Events\User\ResidentBlocked' => [
            'App\Listeners\User\ResidentBlockedListener',
            'App\Listeners\JournalListener',
        ],
        'App\Events\User\ResidentRegistered' => [
            'App\Listeners\User\ResidentRegisteredListener',
            'App\Listeners\JournalListener',
        ],
        'App\Events\User\ResidentTransitionToPartner' => [
            'App\Listeners\User\ResidentTransitionToPartnerListener',
            'App\Listeners\JournalListener',
        ],
        'App\Events\User\ResidentBePartnerRequested' => [
            'App\Listeners\User\ResidentBePartnerRequestedListener',
            'App\Listeners\JournalListener',
        ],
        'App\Events\User\ResidentCuratorChanged' => [
            'App\Listeners\User\ResidentCuratorChangedListener',
            'App\Listeners\JournalListener',
        ],
        // новости       
		'App\Events\News\Registered' => [
            'App\Listeners\NewsListener',
            //'App\Listeners\JournalListener',
        ],
        // заявки
        'App\Events\Requisitions\Registered' => [
            'App\Listeners\RequisitionListener',
            'App\Listeners\JournalListener',
        ],
        'App\Events\Requisitions\ConfirmedByOwner' => [
            'App\Listeners\RequisitionListener',
            'App\Listeners\JournalListener',
        ],
        'App\Events\Requisitions\ConfirmedByCurator' => [
            'App\Listeners\RequisitionListener',
            'App\Listeners\JournalListener',
        ],
        'App\Events\Requisitions\CanceledByCurator' => [
            'App\Listeners\RequisitionListener'
        ],
        'App\Events\Requisitions\ConfirmedByAdmin' => [
            'App\Listeners\RequisitionListener',
            'App\Listeners\JournalListener',
        ],
        'App\Events\Requisitions\CanceledByAdmin' => [
            'App\Listeners\RequisitionListener'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
