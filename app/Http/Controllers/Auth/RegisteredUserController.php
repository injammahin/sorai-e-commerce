<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
class RegisteredUserController extends Controller {public function create(){return view('auth.register');}public function store(Request $r){$data=$r->validate(['name'=>'required|string|max:191','email'=>'required|email|max:191|unique:users','password'=>['required','confirmed',Password::min(8)->mixedCase()->numbers()],'terms'=>'accepted']);$user=User::create(['name'=>$data['name'],'email'=>$data['email'],'password'=>Hash::make($data['password'])]);event(new Registered($user));Auth::login($user);return redirect()->route('verification.notice');}}
