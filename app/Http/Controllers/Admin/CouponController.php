<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class CouponController extends Controller {public function index(){return view('admin.coupons.index',['coupons'=>Coupon::latest()->paginate(30)]);}public function create(){return view('admin.coupons.form',['coupon'=>new Coupon]);}public function store(Request $r){$c=$this->save(new Coupon,$r);return redirect()->route('admin.coupons.edit',$c)->with('success','Coupon created.');}public function edit(Coupon $coupon){return view('admin.coupons.form',compact('coupon'));}public function update(Request $r,Coupon $coupon){$this->save($coupon,$r);return back()->with('success','Coupon updated.');}public function destroy(Coupon $coupon){$coupon->delete();return back()->with('success','Coupon deleted.');}private function save(Coupon $c,Request $r){$d=$r->validate(['code'=>['required','max:30',Rule::unique('coupons')->ignore($c)],'type'=>'required|in:fixed,percent','value'=>'required|numeric|min:0','minimum_order'=>'required|numeric|min:0','maximum_discount'=>'nullable|numeric|min:0','usage_limit'=>'nullable|integer|min:1','starts_at'=>'nullable|date','expires_at'=>'nullable|date|after:starts_at']);$d['code']=strtoupper($d['code']);$d['is_active']=$r->boolean('is_active');$c->fill($d)->save();return $c;}}
