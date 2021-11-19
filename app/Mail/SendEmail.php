<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = env('MAIL_FROM_ADDRESS');
        $reply_to = env('MAIL_FROM_ADDRESS');
        $subject = $this->data['subject'];
        $name = env('APP_NAME');
        return $this->view('emails.send_email')
            ->from($address, $name)
            ->replyTo("$reply_to", $name)
            ->subject($subject)

            ->with(
                [
                    'subject' => $this->data['subject'] ,
                    'text' => $this->data['message']
                ]
            );
    }
}
