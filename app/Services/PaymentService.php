<?php
namespace App\Services;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
class PaymentService {
 public function sslCommerzRedirect(Order $order): string {
  abort_unless(config('services.sslcommerz.enabled'),503,'Online payment is not configured.');$base=config('services.sslcommerz.sandbox')?'https://sandbox.sslcommerz.com':'https://securepay.sslcommerz.com';$a=$order->shipping_address;
  $response=Http::asForm()->post($base.'/gwprocess/v4/api.php',['store_id'=>config('services.sslcommerz.store_id'),'store_passwd'=>config('services.sslcommerz.store_password'),'total_amount'=>$order->total,'currency'=>'BDT','tran_id'=>$order->order_number,'success_url'=>route('payment.success'),'fail_url'=>route('payment.fail'),'cancel_url'=>route('payment.cancel'),'ipn_url'=>route('payment.ipn'),'cus_name'=>$a['name'],'cus_email'=>$a['email'],'cus_phone'=>$a['phone'],'cus_add1'=>$a['address_line_1'],'cus_city'=>$a['city'],'cus_country'=>'Bangladesh','shipping_method'=>'Courier','product_name'=>'SARAI order '.$order->order_number,'product_category'=>'Craft & Lifestyle','product_profile'=>'general']);
  $body=$response->throw()->json();abort_unless(($body['status']??null)==='SUCCESS'&&isset($body['GatewayPageURL']),502,'Payment gateway did not return a checkout URL.');return $body['GatewayPageURL'];
 }
 public function validate(string $validationId): array {$base=config('services.sslcommerz.sandbox')?'https://sandbox.sslcommerz.com':'https://securepay.sslcommerz.com';return Http::get($base.'/validator/api/validationserverAPI.php',['val_id'=>$validationId,'store_id'=>config('services.sslcommerz.store_id'),'store_passwd'=>config('services.sslcommerz.store_password'),'format'=>'json'])->throw()->json();}
}
