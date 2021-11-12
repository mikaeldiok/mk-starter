<?php

namespace Modules\Benefactor\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Benefactor\Emails\NotifyRegistration;
use Mail;

use Modules\Benefactor\Entities\Donator;

class SendRegMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
   
    protected $details;
    protected $donator;
   
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details,Donator $donator)
    {
        $this->details = $details;
        $this->donator = $donator;
    }
   
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new NotifyRegistration($this->donator);
        Mail::to($this->details['email'])->send($email);
    }
}
