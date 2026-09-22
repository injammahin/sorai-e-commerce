<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
class CheckoutController extends Controller {
 public function __construct(private CartService $cart){}
 public function index(){if($this->cart->items()->isEmpty())return redirect()->route('cart.index');return view('store.checkout',['items'=>$this->cart->items(),'totals'=>$this->cart->totals(),'addresses'=>auth()->user()?->addresses??collect()]);}
 public function store(Request $r,OrderService $orders,PaymentService $payments){$data=$r->validate(['name'=>'required|string|max:191','email'=>'required|email|max:191','phone'=>'required|string|max:30','address_line_1'=>'required|string|max:255','address_line_2'=>'nullable|string|max:255','area'=>'nullable|string|max:100','city'=>'required|string|max:100','district'=>'required|string|max:100','postal_code'=>'nullable|string|max:20','payment_method'=>'required|in:cod,bank,sslcommerz','customer_note'=>'nullable|string|max:1000']);$address=collect($data)->only(['name','email','phone','address_line_1','address_line_2','area','city','district','postal_code'])->all();$order=$orders->place(['shipping_address'=>$address,'payment_method'=>$data['payment_method'],'customer_note'=>$data['customer_note']??null]);if($data['payment_method']==='sslcommerz')return redirect()->away($payments->sslCommerzRedirect($order));return redirect()->route('order.thank-you',$order)->with('success','Your order has been placed.');}
 public function thankYou(Order $order){$this->authorizeOrder($order);return view('store.thank-you',compact('order'));}
 public function success(Request $r,PaymentService $payments){$order=Order::where('order_number',$r->input('tran_id'))->firstOrFail();$result=$payments->validate($r->string('val_id'));if(in_array($result['status']??'', ['VALID','VALIDATED'],true)&&abs((float)$result['amount']-(float)$order->total)<0.01){$order->update(['payment_status'=>'paid','transaction_id'=>$result['bank_tran_id']??$r->input('tran_id'),'paid_at'=>now()]);return redirect()->route('order.thank-you',$order)->with('success','Payment completed.');}return redirect()->route('order.thank-you',$order)->withErrors('Payment validation failed.');}
 public function fail(Request $r){$order=Order::where('order_number',$r->input('tran_id'))->firstOrFail();$order->update(['payment_status'=>'failed']);return redirect()->route('order.thank-you',$order)->withErrors('Payment failed. You may contact support to retry.');}public function cancel(Request $r){return $this->fail($r);}public function ipn(Request $r,PaymentService $p){return response()->json(['received'=>true]);}
 private function authorizeOrder(Order $o){abort_unless(request()->hasValidSignature()||(auth()->check()&&($o->user_id===auth()->id()||auth()->user()->isAdmin()))||session()->get('last_order')===$o->id,403);}
}
