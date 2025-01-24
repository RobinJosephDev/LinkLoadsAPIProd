<?php

namespace App\Mail;

use App\Models\Carrier;
use Illuminate\Mail\Mailable;

class CarrierEmailNotification extends Mailable
{
    public $carrier;
    public $subject;
    public $content;

    public function __construct(Carrier $carrier, $subject, $content)
    {
        $this->carrier = $carrier;
        $this->subject = $subject;
        $this->content = $content;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.carrierNotification'); // This must match the view file path
    }
}
