<?php
namespace App\Services;
use App\Mail\NewOrderAdmin;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
class OrderService {
 public function __construct(private CartService $cart){}
 public function place(array $data): Order {
  abort_if($this->cart->items()->isEmpty(),422,'Your cart is empty.');
  $order=DB::transaction(function()use($data){$totals=$this->cart->totals();$coupon=$this->cart->coupon();$order=Order::create(['order_number'=>'SR'.now()->format('ymd').strtoupper(Str::random(6)),'user_id'=>auth()->id(),'coupon_id'=>$coupon?->id,'status'=>'pending','payment_status'=>'unpaid','payment_method'=>$data['payment_method'],'subtotal'=>$totals['subtotal'],'discount'=>$totals['discount'],'shipping'=>$totals['shipping'],'tax'=>$totals['tax'],'total'=>$totals['total'],'shipping_address'=>$data['shipping_address'],'billing_address'=>$data['billing_address']??$data['shipping_address'],'customer_note'=>$data['customer_note']??null]);
   foreach($this->cart->items() as $item){$product=Product::active()->lockForUpdate()->findOrFail($item->product_id);$variant=$item->variant_id?ProductVariant::where('is_active',true)->lockForUpdate()->findOrFail($item->variant_id):null;$stock=$variant?->stock??$product->stock;abort_if($stock<$item->quantity,422,"{$product->name} no longer has enough stock.");if($variant){$variant->decrement('stock',$item->quantity);}else{$product->decrement('stock',$item->quantity);}$order->items()->create(['product_id'=>$product->id,'product_variant_id'=>$variant?->id,'product_name'=>$product->name,'sku'=>$variant?->sku??$product->sku,'image'=>$product->primaryImage->path,'color'=>$item->color,'size'=>$item->size,'unit_price'=>$item->price,'quantity'=>$item->quantity,'total'=>$item->line_total]);}
   $order->histories()->create(['user_id'=>auth()->id(),'status'=>'pending','note'=>'Order placed by customer.']);if($coupon)$coupon->increment('used_count');return $order->load('items');});
  $this->cart->clear(); session(['last_order'=>$order->id]); rescue(fn()=>Mail::to($order->shipping_address['email'])->queue(new OrderConfirmation($order))); $admin=\App\Models\Setting::valueOf('order_notification_email',env('ADMIN_EMAIL'));if($admin)rescue(fn()=>Mail::to($admin)->queue(new NewOrderAdmin($order)));return $order;
 }
}
