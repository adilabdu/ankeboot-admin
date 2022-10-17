<?php

namespace App\Mail;

use App\Models\MailingList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriberWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public MailingList $mailingList;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(MailingList $mailingList)
    {
        $this->mailingList = $mailingList;
    }

    public function envelope()
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.welcome');
    }
}
