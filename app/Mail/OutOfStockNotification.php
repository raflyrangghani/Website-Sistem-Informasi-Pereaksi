<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OutOfStockNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $pereaksi;
    public $status;
    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        $this->pereaksi = $pereaksi;
        $this->status = $status;
    }
    
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Out Of Stock Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->view('emails.OutOfStockNotification')
                    ->with([
                        'pereaksi' => $this->pereaksi,
                        'status' => $this->status,
                    ]);
    
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
