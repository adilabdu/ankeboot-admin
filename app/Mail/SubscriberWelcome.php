<?php

namespace App\Mail;

use App\Models\MailingList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriberWelcome extends Mailable implements ShouldQueue
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

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('contact@ankeboot.com', 'Ankeboot Publishing + Bookstore'),
            subject: 'Thanks for joining our network!'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome'
        );
    }
}
