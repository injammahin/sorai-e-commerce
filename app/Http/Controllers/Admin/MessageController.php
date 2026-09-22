<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
class MessageController extends Controller {public function index(){return view('admin.messages.index',['messages'=>ContactMessage::latest()->paginate(30)]);}public function show(ContactMessage $message){if($message->status==='new')$message->update(['status'=>'read']);return view('admin.messages.show',compact('message'));}public function update(Request $r,ContactMessage $message){$message->update($r->validate(['status'=>'required|in:new,read,replied,closed']));return back()->with('success','Message updated.');}public function destroy(ContactMessage $message){$message->delete();return redirect()->route('admin.messages.index')->with('success','Message deleted.');}}
