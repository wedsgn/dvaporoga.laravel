<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestCarMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $details;

    public function __construct(array $details) { $this->details = $details; }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->details['subject'] ?? 'Заявка по авто');
    }

    public function build(): self
    {
        // передаём ВСЁ, включая make/model/car/form и т.п.
        return $this->view('emails.request_car_email')
            ->with(['details' => $this->details]);
    }
}
