<?php

namespace Modules\Benefactor\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Modules\Benefactor\Entities\Donator;
use Modules\Benefactor\Jobs\SendRegMail;

class NotifyDonator
{
    public $donator;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $this->donator = $event->donator;
        
        $details['email'] = $this->donator->donator_email;
        
        dispatch(new SendRegMail($details, $this->donator));
    }
}
