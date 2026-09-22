<?php
namespace Tests\Feature;
use App\Mail\OrderConfirmation;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
class CommerceFlowTest extends TestCase {use RefreshDatabase;
 private function product(): Product {$c=Category::create(['name'=>'Home','slug'=>'home','is_active'=>true]);$p=Product::create(['category_id'=>$c->id,'name'=>'Kantha Cushion','slug'=>'kantha-cushion','sku'=>'TEST-1','price'=>1000,'stock'=>5,'is_active'=>true]);$p->images()->create(['path'=>'images/products/test.webp','is_primary'=>true]);return $p;}
 public function test_cart_uses_database_price_and_enforces_stock(): void {$p=$this->product();$this->post(route('cart.store',$p),['quantity'=>2,'price'=>1])->assertSessionHasNoErrors();$this->assertSame(2000.0,app(CartService::class)->subtotal());$this->post(route('cart.store',$p),['quantity'=>9])->assertStatus(422);}
 public function test_order_is_created_atomically_and_stock_is_decremented(): void {Mail::fake();Setting::create(['key'=>'shipping_charge','value'=>'120']);Setting::create(['key'=>'free_shipping_threshold','value'=>'5000']);$p=$this->product();app(CartService::class)->add($p,2);$order=app(OrderService::class)->place(['payment_method'=>'cod','shipping_address'=>['name'=>'Test Buyer','email'=>'buyer@example.com','phone'=>'01700000000','address_line_1'=>'Dhaka','city'=>'Dhaka','district'=>'Dhaka']]);$this->assertSame('2120.00',$order->total);$this->assertSame(3,$p->fresh()->stock);$this->assertDatabaseHas('order_items',['order_id'=>$order->id,'quantity'=>2,'unit_price'=>1000]);Mail::assertQueued(OrderConfirmation::class);}
}
