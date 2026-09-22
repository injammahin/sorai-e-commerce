<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
class EmailVerificationController extends Controller {public function notice(){return view('auth.verify-email');}public function verify(EmailVerificationRequest $r){$r->fulfill();return redirect()->route('account.index')->with('success','Email verified.');}public function send(Request $r){if(!$r->user()->hasVerifiedEmail())$r->user()->sendEmailVerificationNotification();return back()->with('success','Verification link sent.');}}
