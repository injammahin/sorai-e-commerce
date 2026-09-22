<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthenticatedSessionController extends Controller {public function create(){return view('auth.login');}public function store(Request $r){$credentials=$r->validate(['email'=>'required|email','password'=>'required|string']);if(!Auth::attempt($credentials,$r->boolean('remember'))){return back()->withErrors(['email'=>'These credentials do not match our records.'])->onlyInput('email');}$r->session()->regenerate();abort_unless($r->user()->is_active,403,'Your account is disabled.');return redirect()->intended($r->user()->isAdmin()?route('admin.dashboard'):route('account.index'));}public function destroy(Request $r){Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('home');}}
