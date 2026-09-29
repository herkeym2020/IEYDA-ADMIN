<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailSubject;
    public $emailBody;
    public $recipientName;
    public $registrationNumber;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $body, $name = null, $registrationNumber = null)
    {
        $this->emailSubject = $subject;
        $this->emailBody = $body;
        $this->recipientName = $name;
        $this->registrationNumber = $registrationNumber;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject($this->emailSubject)
                    ->view('emails.generic');
    }
}
