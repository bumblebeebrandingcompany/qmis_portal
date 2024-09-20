<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpToMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $otp;


    public function __construct(Lead $lead,$otp)
    {
        $this->lead = $lead;
        $this->otp = $otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('One Time Password')
                    ->view('emails.otp');
    }
}
