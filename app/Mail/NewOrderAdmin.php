<?php
namespace App\Mail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class NewOrderAdmin extends Mailable implements ShouldQueue {use Queueable,SerializesModels;public function __construct(public Order $order){}public function build(){return $this->subject('New order '.$this->order->order_number)->view('emails.new-order-admin');}}
