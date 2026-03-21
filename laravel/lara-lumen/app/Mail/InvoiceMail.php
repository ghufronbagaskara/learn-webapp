<?php // app/Mail/InvoiceMail.php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
  use Queueable, SerializesModels;

  public Order $order;

  public function __construct(Order $order)
  {
    $this->order = $order;
  }

  public function build(): self
  {
    return $this->subject('Invoice Pesanan #' . $this->order->id)
      ->view('emails.invoice');
  }
}
