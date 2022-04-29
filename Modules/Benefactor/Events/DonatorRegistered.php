<?php

namespace Modules\Benefactor\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Benefactor\Entities\Donator;

class DonatorRegistered
{
    use SerializesModels;

    public $donator;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Donator $donator)
    {
        $this->donator = $donator;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
