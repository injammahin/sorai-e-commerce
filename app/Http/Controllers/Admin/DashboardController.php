<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller {public function __invoke(){$sales=Order::where('payment_status','paid')->where('created_at','>=',now()->subMonths(11)->startOfMonth())->oldest()->get(['created_at','total'])->groupBy(fn($o)=>$o->created_at->format('Y-m'))->map(fn($rows,$month)=>(object)['month'=>$month,'total'=>$rows->sum('total')])->values();return view('admin.dashboard',['stats'=>['orders'=>Order::count(),'revenue'=>Order::where('payment_status','paid')->sum('total'),'customers'=>User::where('role','customer')->count(),'low_stock'=>Product::whereColumn('stock','<=','low_stock_threshold')->count(),'messages'=>ContactMessage::where('status','new')->count()],'recentOrders'=>Order::with('user')->latest()->limit(8)->get(),'sales'=>$sales]);}}
