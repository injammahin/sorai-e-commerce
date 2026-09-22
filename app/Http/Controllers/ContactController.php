<?php
namespace App\Http\Controllers;
use App\Models\ContactMessage;
use App\Models\Subscriber;
use Illuminate\Http\Request;
class ContactController extends Controller {public function store(Request $r){$data=$r->validate(['name'=>'required|max:191','email'=>'required|email|max:191','phone'=>'nullable|max:30','subject'=>'nullable|max:191','message'=>'required|string|max:3000','website'=>'nullable|max:0']);ContactMessage::create($data);return back()->with('success','Thank you. We will reply shortly.');}public function subscribe(Request $r){$email=$r->validate(['email'=>'required|email|max:191'])['email'];Subscriber::updateOrCreate(['email'=>$email],['is_active'=>true,'subscribed_at'=>now(),'unsubscribed_at'=>null]);return back()->with('success','Welcome to the SARAI journal.');}}
