<?php
namespace App\Http\Controllers;
use App\Models\Product;
class WishlistController extends Controller {public function index(){return view('store.wishlist',['products'=>auth()->user()->wishlistProducts()->active()->with('images')->paginate(20)]);}public function toggle(Product $product){auth()->user()->wishlistProducts()->toggle($product->id);return back()->with('success','Wishlist updated.');}}
