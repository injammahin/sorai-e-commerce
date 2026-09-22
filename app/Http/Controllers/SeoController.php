<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
class SeoController extends Controller {public function sitemap(){return response()->view('sitemap',['products'=>Product::active()->select('slug','updated_at')->get(),'categories'=>Category::active()->select('slug','updated_at','parent_id')->get(),'collections'=>Collection::where('is_active',true)->select('slug','updated_at')->get(),'pages'=>Page::where('is_active',true)->select('slug','updated_at')->get(),'posts'=>Post::published()->select('slug','updated_at')->get()])->header('Content-Type','application/xml');}public function robots(){return response("User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /account\nDisallow: /checkout\nSitemap: ".url('/sitemap.xml')."\n",200,['Content-Type'=>'text/plain']);}}
