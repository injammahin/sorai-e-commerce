<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
class ReviewController extends Controller {public function store(Request $r,Product $product){$data=$r->validate(['order_id'=>'required|exists:orders,id','rating'=>'required|integer|between:1,5','title'=>'nullable|max:120','comment'=>'required|string|max:2000']);$order=Order::whereKey($data['order_id'])->where('user_id',$r->user()->id)->where('status','delivered')->whereHas('items',fn($q)=>$q->where('product_id',$product->id))->firstOrFail();$product->reviews()->updateOrCreate(['user_id'=>$r->user()->id,'order_id'=>$order->id],$data+['is_verified_purchase'=>true,'status'=>'pending']);return back()->with('success','Thank you. Your review is awaiting approval.');}}
