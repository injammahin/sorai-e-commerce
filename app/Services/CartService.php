<?php
namespace App\Services;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;

class CartService
{
    private string $key='aatchala_cart';
    public function raw(): array { return session($this->key,[]); }
    public function add(Product $product,int $quantity=1,?int $variantId=null,?string $color=null,?string $size=null): void {
        abort_unless($product->is_active,404); $quantity=max(1,min($quantity,10));
        $variant=$variantId?ProductVariant::where('product_id',$product->id)->where('is_active',true)->findOrFail($variantId):null;
        $available=$variant?->stock??$product->stock; abort_if($available<$quantity,422,'The selected quantity is not available.');
        $key=implode(':',[$product->id,$variantId?:0,$color?:'-',$size?:'-']); $cart=$this->raw();
        $cart[$key]=['product_id'=>$product->id,'variant_id'=>$variant?->id,'quantity'=>min(($cart[$key]['quantity']??0)+$quantity,$available,10),'color'=>$variant?->color??$color,'size'=>$variant?->size??$size];
        session([$this->key=>$cart]);
    }
    public function update(string $key,int $quantity): void { $cart=$this->raw(); if(!isset($cart[$key]))return; if($quantity<1){unset($cart[$key]);}else{$item=$cart[$key];$product=Product::active()->findOrFail($item['product_id']);$variant=$item['variant_id']?ProductVariant::where('is_active',true)->find($item['variant_id']):null;$available=$variant?->stock??$product->stock;if($available<1)unset($cart[$key]);else $cart[$key]['quantity']=min($quantity,$available,10);} session([$this->key=>$cart]); }
    public function remove(string $key): void {$cart=$this->raw();unset($cart[$key]);session([$this->key=>$cart]);}
    public function clear(): void {session()->forget([$this->key,'aatchala_coupon']);}
    public function items(): Collection { $raw=$this->raw();$products=Product::active()->with('primaryImage')->whereIn('id',collect($raw)->pluck('product_id'))->get()->keyBy('id');$variants=ProductVariant::where('is_active',true)->whereIn('id',collect($raw)->pluck('variant_id')->filter())->get()->keyBy('id');return collect($raw)->map(function($item,$key)use($products,$variants){$product=$products->get($item['product_id']);if(!$product)return null;$variant=$item['variant_id']?$variants->get($item['variant_id']):null;if($item['variant_id']&&!$variant)return null;$price=(float)($variant?->price?:$product->price);return(object)array_merge($item,['key'=>$key,'product'=>$product,'variant'=>$variant,'price'=>$price,'line_total'=>$price*$item['quantity']]);})->filter()->values(); }
    public function subtotal(): float {return $this->items()->sum('line_total');}
    public function coupon(): ?Coupon { $id=session('aatchala_coupon');return $id?Coupon::find($id):null; }
    public function applyCoupon(string $code): Coupon {$coupon=Coupon::whereRaw('UPPER(code)=?',[strtoupper(trim($code))])->firstOrFail();abort_unless($coupon->isValidFor($this->subtotal()),422,'This coupon cannot be applied to your cart.');session(['aatchala_coupon'=>$coupon->id]);return $coupon;}
    public function totals(): array {$subtotal=$this->subtotal();$coupon=$this->coupon();$discount=$coupon&&$coupon->isValidFor($subtotal)?(float)$coupon->discountFor($subtotal):0;$freeLimit=(float)\App\Models\Setting::valueOf('free_shipping_threshold',5000);$shipping=$subtotal<=0||$subtotal>=$freeLimit?0:(float)\App\Models\Setting::valueOf('shipping_charge',120);$taxRate=(float)\App\Models\Setting::valueOf('tax_rate',0);$tax=max(0,($subtotal-$discount)*$taxRate/100);return compact('subtotal','discount','shipping','tax')+['total'=>max(0,$subtotal-$discount+$shipping+$tax)];}
    public function count(): int {return collect($this->raw())->sum('quantity');}
}
