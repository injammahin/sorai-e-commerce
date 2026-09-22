<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
class AccountController extends Controller {public function index(){return view('account.index',['orders'=>auth()->user()->orders()->with('items')->latest()->paginate(10)]);}public function update(Request $r){$data=$r->validate(['name'=>'required|max:191','phone'=>'nullable|max:30']);$r->user()->update($data);return back()->with('success','Profile updated.');}public function password(Request $r){$data=$r->validate(['current_password'=>'required|current_password','password'=>['required','confirmed',Password::min(8)->mixedCase()->numbers()]]);$r->user()->update(['password'=>Hash::make($data['password'])]);return back()->with('success','Password changed.');}}
