<?php

namespace App\Events\User;

use App\Models\BePartnerRequest;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Переход резидента в партнеры
 *
 * @package App\Events\User
 */
class ResidentTransitionToPartner
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bePartnerRequest;

    public function __construct(BePartnerRequest $bePartnerRequest)
    {
        $this->bePartnerRequest = $bePartnerRequest;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
