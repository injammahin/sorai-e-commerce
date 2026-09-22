<?php
namespace App\Mail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
class OrderStatusUpdated extends Mailable implements ShouldQueue {use Queueable,SerializesModels;public function __construct(public Order $order){}public function build(){return $this->subject('Order '.$this->order->order_number.' is now '.$this->order->status)->view('emails.order-status')->with(['orderUrl'=>URL::temporarySignedRoute('order.thank-you',now()->addDays(30),['order'=>$this->order])]);}}
