<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;

class RestaurantRegistrationMail extends Mailable
{
    use Queueable;

    public $details; // Data to pass to the email view

    /**
     * Create a new message instance.
     *
     * @param array $details
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome Email')
            ->view('emails.restaurantRegistrationEmail'); // Blade view for the email content
    }
}
