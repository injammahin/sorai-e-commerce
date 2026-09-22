<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
class GoogleController extends Controller {public function redirect(){abort_unless(config('services.google.client_id'),503,'Google login is not configured.');return Socialite::driver('google')->redirect();}public function callback(){try{$google=Socialite::driver('google')->user();}catch(\Throwable $e){report($e);return redirect()->route('login')->withErrors('Google sign-in could not be completed.');}$user=User::where('google_id',$google->id)->orWhere('email',$google->email)->first();if($user){$user->update(['google_id'=>$google->id,'avatar'=>$user->avatar?:$google->avatar,'email_verified_at'=>$user->email_verified_at?:now()]);}else{$user=User::create(['name'=>$google->name?:'SARAI Customer','email'=>$google->email,'google_id'=>$google->id,'avatar'=>$google->avatar,'email_verified_at'=>now(),'password'=>bcrypt(Str::random(40))]);}Auth::login($user,true);request()->session()->regenerate();return redirect()->intended(route('account.index'));}}
