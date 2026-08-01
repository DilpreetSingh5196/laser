<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public ?Order $order;
    public string $notifTitle;
    public string $notifMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(?Order $order, string $notifTitle, string $notifMessage)
    {
        $this->order = $order;
        $this->notifTitle = $notifTitle;
        $this->notifMessage = $notifMessage;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->notifTitle . ' - ' . \App\Models\Setting::get('company_name', 'Jai Maa Durga'))
                    ->view('emails.order_notification');
    }
}
