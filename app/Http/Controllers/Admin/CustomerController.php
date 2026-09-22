<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
class CustomerController extends Controller {public function index(Request $r){$customers=User::where('role','customer')->withCount('orders')->when($r->filled('q'),fn($q)=>$q->where(fn($x)=>$x->where('name','like','%'.$r->q.'%')->orWhere('email','like','%'.$r->q.'%')))->latest()->paginate(25)->withQueryString();return view('admin.customers.index',compact('customers'));}public function show(User $customer){abort_unless($customer->role==='customer',404);return view('admin.customers.show',['customer'=>$customer->load(['orders'=>fn($q)=>$q->latest(),'addresses'])]);}public function toggle(User $customer){abort_unless($customer->role==='customer',403);$customer->update(['is_active'=>!$customer->is_active]);return back()->with('success','Customer status updated.');}}
