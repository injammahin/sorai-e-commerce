<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Subscriber;
class SubscriberController extends Controller {public function index(){return view('admin.subscribers.index',['subscribers'=>Subscriber::latest()->paginate(50)]);}public function export(){return response()->streamDownload(function(){echo "email,subscribed_at,status\n";Subscriber::orderBy('id')->chunk(500,function($rows){foreach($rows as $r)echo '"'.str_replace('"','""',$r->email).'",'.$r->subscribed_at.','.($r->is_active?'active':'inactive')."\n";});},'sarai-subscribers-'.date('Y-m-d').'.csv',['Content-Type'=>'text/csv']);}}
