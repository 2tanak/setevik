<?php

namespace App\Listeners;

use App\Models\Journal;
use App\Models\EventType;

use App\Events\User\PartnerActivated;
use App\Events\User\PartnerBlocked;
use App\Events\User\PartnerDeactivated;
use App\Events\User\PartnerRegistered;
use App\Events\User\PartnerUpgraded;

use App\Events\User\ResidentActivated;
use App\Events\User\ResidentBlocked;
use App\Events\User\ResidentRegistered;
use App\Events\User\ResidentTransitionToPartner;
use App\Events\User\ResidentBePartnerRequested;
use App\Events\User\ResidentCuratorChanged;

use App\Events\Requisitions\ConfirmedByOwner;
use App\Events\Requisitions\ConfirmedByAdmin;
use App\Events\Requisitions\ConfirmedByCurator;
use App\Events\Requisitions\Registered as RequisitionRegistered;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class JournalListener
{
    /**
     * Handle the event.
     *
     * @param mixed $event
     * @return void
     */
    public function handle($event)
    {
        if ($event instanceof PartnerActivated) {
            $this->partnerActivated($event);
        } elseif ($event instanceof PartnerBlocked) {
            $this->partnerBlocked($event);
        } elseif ($event instanceof PartnerDeactivated) {
            $this->partnerDeactivated($event);
        } elseif ($event instanceof PartnerRegistered) {
            $this->partnerRegistered($event);
        } elseif ($event instanceof PartnerUpgraded) {
            $this->partnerUpgraded($event);
        } elseif ($event instanceof ResidentActivated) {
            $this->residentActivated($event);
        } elseif ($event instanceof ResidentBlocked) {
            $this->residentBlocked($event);
        } elseif ($event instanceof ResidentRegistered) {
            $this->residentRegistered($event);
        } elseif ($event instanceof ResidentTransitionToPartner) {
            $this->residentTransitionToPartner($event);
        } elseif ($event instanceof ResidentBePartnerRequested) {
            $this->residentBePartnerRequested($event);
        } elseif ($event instanceof ResidentCuratorChanged) {
            $this->residentCuratorChanged($event);
        } elseif ($event instanceof RequisitionRegistered) {
            $this->requisitionRegistered($event);
        } elseif ($event instanceof ConfirmedByOwner) {
            $this->confirmedByOwner($event);
        } elseif ($event instanceof ConfirmedByCurator) {
            $this->confirmedByCurator($event);
        } elseif ($event instanceof ConfirmedByAdmin) {
            $this->confirmedByAdmin($event);
        }
    }

    /**
     * Create data for journal
     *
     * @param $data
     * @param $slug
     */
    protected function journalCreate($slug, $data)
    {
        Journal::create([
            'event_type_id' => EventType::where('slug', $slug)->firstOrFail()->id,
            'data'          => $data,
        ]);
        \Log::info('journal: '.$slug);
    }

    public function partnerRegistered($event)
    {
        $this->journalCreate('partner-registered', $event->user);
    }

    public function partnerActivated($event)
    {
        $this->journalCreate('partner-activated', $event->user);
    }

    public function partnerBlocked($event)
    {
        $this->journalCreate('partner-blocked', $event->user);
    }

    public function partnerDeactivated($event)
    {
        $this->journalCreate('partner-deactivated', $event->user);
    }

    public function partnerUpgraded($event)
    {
        $this->journalCreate('partner-upgraded', $event->user);
    }

    public function residentRegistered($event)
    {
        $this->journalCreate('resident-registered', $event->user);
    }

    public function residentActivated($event)
    {
        $this->journalCreate('resident-activated', $event->user);
    }

    public function residentBlocked($event)
    {
        $this->journalCreate('resident-blocked', $event->user);
    }

    public function residentTransitionToPartner($event)
    {
        $this->journalCreate('resident-transition-to-partner', $event->bePartnerRequest);
    }

    public function residentBePartnerRequested($event)
    {
        $this->journalCreate('resident-be-partner-request', $event->user);
    }

    public function residentCuratorChanged($event)
    {
        $this->journalCreate('resident-curator-changed', $event->user);
    }

    public function requisitionRegistered($event)
    {
        $this->journalCreate('requisition-registered', $event->requisition);
    }

    public function confirmedByOwner($event)
    {
        $this->journalCreate('requisition-owner-confirm', $event->requisition);
    }

    public function confirmedByCurator($event)
    {
        $this->journalCreate('requisition-curator-confirm', $event->requisition);
    }

    public function confirmedByAdmin($event)
    {
        $this->journalCreate('requisition-admin-confirm', $event->requisition);
    }
}
