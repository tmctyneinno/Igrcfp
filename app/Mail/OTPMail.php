<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTPMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp; 
 
    public function __construct($otp)
    {
        $this->otp = $otp;
    }


    public function build()
    {
        return $this->from('onboarding@resend.dev', 'IGRCFP')
                    ->subject('Your Login Verification Code')
                    ->view('emails.otp');
    }
}