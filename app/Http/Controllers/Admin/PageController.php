<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class PageController extends Controller {public function __construct(private MediaService $media,private \App\Services\HtmlSanitizer $sanitizer){}public function index(){return view('admin.pages.index',['pages'=>Page::orderBy('title')->paginate(30)]);}public function create(){return view('admin.pages.form',['page'=>new Page]);}public function store(Request $r){$p=$this->save(new Page,$r);return redirect()->route('admin.pages.edit',$p)->with('success','Page created.');}public function edit(Page $page){return view('admin.pages.form',compact('page'));}public function update(Request $r,Page $page){$this->save($page,$r);return back()->with('success','Page updated.');}public function destroy(Page $page){$page->delete();return back()->with('success','Page deleted.');}private function save(Page $p,Request $r){$d=$r->validate(['title'=>'required|max:191','slug'=>['required','alpha_dash',Rule::unique('pages')->ignore($p)],'content'=>'nullable|string','hero_image'=>'nullable|image|max:6144','meta_title'=>'nullable|max:70','meta_description'=>'nullable|max:170']);$d['content']=$this->sanitizer->clean($d['content']??'');if($r->hasFile('hero_image')){$this->media->delete($p->hero_image);$d['hero_image']=$this->media->store($r->file('hero_image'),'pages');}$d['is_active']=$r->boolean('is_active');$p->fill($d)->save();return $p;}}
