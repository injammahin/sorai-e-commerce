<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class ProductController extends Controller {
 public function __construct(private MediaService $media){}
 public function index(Request $r){$products=Product::with(['category','primaryImage'])->search($r->input('q'))->when($r->filled('status'),fn($q)=>$q->where('is_active',$r->input('status')==='active'))->latest()->paginate(20)->withQueryString();return view('admin.products.index',compact('products'));}
 public function create(){return view('admin.products.form',['product'=>new Product,'categories'=>Category::whereNull('parent_id')->with('children')->ordered()->get()]);}
 public function store(ProductRequest $r){$product=$this->save(new Product,$r);return redirect()->route('admin.products.edit',$product)->with('success','Product created.');}
 public function edit(Product $product){$product->load('images');return view('admin.products.form',['product'=>$product,'categories'=>Category::whereNull('parent_id')->with('children')->ordered()->get()]);}
 public function update(ProductRequest $r,Product $product){$this->save($product,$r);return back()->with('success','Product updated.');}
 public function destroy(Product $product){$product->delete();return back()->with('success','Product moved to trash.');}
 public function removeImage(Product $product,$image){$img=$product->images()->findOrFail($image);$this->media->delete($img->path);$img->delete();if(!$product->images()->where('is_primary',true)->exists())$product->images()->first()?->update(['is_primary'=>true]);return back()->with('success','Image removed.');}
 private function save(Product $product,ProductRequest $r){return DB::transaction(function()use($product,$r){$d=$r->validated();$palette=['Ivory'=>'#efe6d6','Cream'=>'#e8ddc9','Indigo'=>'#22344f','Madder'=>'#8e2f2a','Terracotta'=>'#b4572f','Copper'=>'#a8542a','Gold'=>'#b98d33','Wine'=>'#6b2233','Teal'=>'#245b56','Olive'=>'#5f6a3c','Charcoal'=>'#2c2f33','Sage'=>'#8c9a86','Sand'=>'#c8b394'];$d['colors']=collect(explode(',',$d['colors']??''))->map(fn($x)=>trim($x))->filter()->map(fn($name)=>['name'=>$name,'hex'=>$palette[$name]??'#c9c4bb'])->values()->all();foreach(['sizes','tags'] as $f)$d[$f]=collect(explode(',',$d[$f]??''))->map(fn($x)=>trim($x))->filter()->values()->all();foreach(['is_active','is_featured','is_new','is_bestseller','is_limited'] as $f)$d[$f]=$r->boolean($f);$product->fill($d)->save();$existing=$product->images()->count();$files=$r->file('images',[]);abort_if($existing+count($files)>6,422,'A product can have no more than six images.');$next=(int)$product->images()->max('sort_order')+1;foreach($files as $i=>$file){$product->images()->create(['path'=>$this->media->store($file,'products'),'alt_text'=>$r->input("image_alt.$i")?:$product->name,'sort_order'=>$next+$i,'is_primary'=>$existing===0&&$i===0]);}return $product;});}
}
