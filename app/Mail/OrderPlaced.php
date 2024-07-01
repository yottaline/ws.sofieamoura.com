<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public $order_code;
    public $placed;
    public $order;

    public function __construct($order_code, $placed, $order)
    {
        $this->order_code = $order_code;
        $this->placed = $placed;
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Order Placed-' .  $this->order_code .'-'.  $this->placed)
                    ->view('email.orderPlaced')
                    ->with('order' , $this->order);
    }

}