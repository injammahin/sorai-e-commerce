<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
class SettingController extends Controller {public function index(){return view('admin.settings.index',['settings'=>Setting::orderBy('group')->orderBy('key')->get()->groupBy('group')]);}public function update(Request $r){$data=$r->validate(['settings'=>'required|array','settings.*'=>'nullable|string|max:10000']);foreach($data['settings'] as $key=>$value)Setting::where('key',$key)->update(['value'=>$value]);return back()->with('success','Settings saved.');}}
