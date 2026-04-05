<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewsletterConfirmMail extends Mailable implements ShouldQueue
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   */
  public function __construct(public readonly Subscriber $subscriber) {}

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'Konfirmasi Langganan Newsletter ABC Blog',
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    return new Content(
      view: 'emails.newsletter-confirm',
      with: [
        'confirmUrl' => route('newsletter.confirm', $this->subscriber->token),
        'unsubscribeUrl' => route('newsletter.unsubscribe', $this->subscriber->token),
        'email' => $this->subscriber->email,
      ],
    );
  }

  /**
   * Get the attachments for the message.
   *
   * @return array<int, Attachment>
   */
  public function attachments(): array
  {
    return [];
  }
}
