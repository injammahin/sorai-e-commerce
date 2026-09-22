<?php
namespace App\Http\Controllers;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
class StoreController extends Controller {
 public function home(){return view('store.home',['banners'=>Banner::live()->where('placement','home_hero')->get(),'featured'=>Product::active()->where('is_featured',true)->with('images')->latest()->limit(8)->get(),'newArrivals'=>Product::active()->where('is_new',true)->with('images')->latest()->limit(8)->get(),'collections'=>Collection::where('is_active',true)->limit(4)->get(),'posts'=>Post::published()->latest('published_at')->limit(3)->get()]);}
 public function category(Category $category,Request $request){abort_unless($category->is_active,404);$children=$category->children()->active()->ordered()->get();$products=Product::active()->where('category_id',$category->id)->with('images')->latest()->limit(8)->get();return view('store.category-landing',compact('category','children','products'));}
 public function products(Request $request,Category $category,?Category $subcategory=null){if($subcategory&&$subcategory->parent_id!==$category->id)abort(404);$q=Product::active()->where('category_id',$category->id)->with('images');if($subcategory)$q->where('subcategory_id',$subcategory->id);$this->applyFilters($q,$request);$products=$q->paginate(16)->withQueryString();return view('store.products',compact('category','subcategory','products'));}
 public function collection(Collection $collection,Request $request){abort_unless($collection->is_active,404);$q=$collection->products()->active()->with('images');$this->applyFilters($q,$request);$products=$q->paginate(16)->withQueryString();return view('store.collection',compact('collection','products'));}
 public function newArrivals(Request $request){$q=Product::active()->where('is_new',true)->with('images');$this->applyFilters($q,$request);$products=$q->paginate(16)->withQueryString();return view('store.products',compact('products')+['pageTitle'=>'New Arrivals','category'=>null,'subcategory'=>null]);}
 public function product(Product $product){abort_unless($product->is_active,404);$product->load(['images','variants'=>fn($q)=>$q->where('is_active',true),'category','subcategory']);$related=Product::active()->where('id','!=',$product->id)->where(fn($q)=>$q->where('subcategory_id',$product->subcategory_id)->orWhere('category_id',$product->category_id))->with('images')->limit(8)->get();return view('store.product',compact('product','related'));}
 public function search(Request $request){$term=trim($request->string('q'));$products=Product::active()->search($term)->with('images')->paginate(20)->withQueryString();return view('store.search',compact('products','term'));}
 public function journal(){return view('store.journal',['posts'=>Post::published()->latest('published_at')->paginate(12)]);} public function post(Post $post){abort_unless($post->is_published&&$post->published_at?->isPast(),404);return view('store.post',compact('post'));}
 public function page(Page $page){abort_unless($page->is_active,404);return view('pages.show',compact('page'));}
 private function applyFilters($q,Request $r){$q->when($r->filled('min'),fn($q)=>$q->where('price','>=',$r->input('min')))->when($r->filled('max'),fn($q)=>$q->where('price','<=',$r->input('max')))->when($r->filled('material'),fn($q)=>$q->where('material','like','%'.$r->input('material').'%'));match($r->input('sort')){'price_asc'=>$q->orderBy('price'),'price_desc'=>$q->orderByDesc('price'),'rating'=>$q->orderByDesc('rating'),default=>$q->latest()};}
}
