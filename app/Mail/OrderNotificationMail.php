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
    public array $attachmentsList;

    /**
     * Create a new message instance.
     */
    public function __construct(?Order $order, string $notifTitle, string $notifMessage, array $attachmentsList = [])
    {
        $this->order = $order;
        $this->notifTitle = $notifTitle;
        $this->notifMessage = $notifMessage;
        $this->attachmentsList = $attachmentsList;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $mail = $this->subject($this->notifTitle . ' - ' . \App\Models\Setting::get('company_name', 'Jai Maa Durga'))
                     ->view('emails.order_notification', [
                         'attachmentsList' => $this->attachmentsList,
                     ]);

        foreach ($this->attachmentsList as $att) {
            if (!empty($att['path']) && file_exists($att['path'])) {
                $mail->attach($att['path'], [
                    'as' => $att['name'] ?? 'attachment',
                    'mime' => $att['mime'] ?? 'application/octet-stream',
                ]);
            }
        }

        return $mail;
    }
}
