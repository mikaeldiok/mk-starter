<?php

namespace Modules\Benefactor\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Modules\Benefactor\Entities\Donator;

class NotifyRegistration extends Mailable
{
    protected $donator;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Donator $donator)
    {
        $this->donator = $donator;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $donator = $this->donator;
        return $this->view('benefactor::email.registration-email')->with('donator', $donator)
                    ->subject('Notifikasi Pendaftaran Donatur');
    }
}
