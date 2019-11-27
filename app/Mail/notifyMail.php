<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class notifyMail extends Mailable
{
    public $mailData;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $datas = $this->mailData;
        return $this->markdown('notifyMail')
                    ->with(['mailData' => $datas])
                    ->subject($datas['subject'])
                    ->to($datas['email'])
                    ->from(env('APP_NAME', 'GO-RINSE').' <'.config('mail.username'));
    }
}
