<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
class PasswordResetController extends Controller {public function request(){return view('auth.forgot-password');}public function email(Request $r){$r->validate(['email'=>'required|email']);$status=Password::sendResetLink($r->only('email'));return $status===Password::RESET_LINK_SENT?back()->with('success',__($status)):back()->withErrors(['email'=>__($status)]);}public function resetForm(Request $r,string $token){return view('auth.reset-password',['request'=>$r,'token'=>$token]);}public function reset(Request $r){$r->validate(['token'=>'required','email'=>'required|email','password'=>['required','confirmed',Rules\Password::defaults()]]);$status=Password::reset($r->only('email','password','password_confirmation','token'),function(User $u,string $p){$u->forceFill(['password'=>Hash::make($p),'remember_token'=>Str::random(60)])->save();event(new PasswordReset($u));});return $status===Password::PASSWORD_RESET?redirect()->route('login')->with('success',__($status)):back()->withErrors(['email'=>__($status)]);}}
