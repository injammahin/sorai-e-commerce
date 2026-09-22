<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view(
            'auth.register'
        );
    }


    public function store(
        Request $request
    ) {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:191',
            ],

            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:191',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',

                Password::min(8)
                    ->mixedCase()
                    ->numbers(),
            ],

            'terms' => [
                'accepted',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create customer
        |--------------------------------------------------------------------------
        */
        $user = User::create([
            'name' =>
                $data['name'],

            'email' =>
                strtolower(
                    $data['email']
                ),

            'password' =>
                Hash::make(
                    $data['password']
                ),

            'role' =>
                'customer',

            'is_active' =>
                true,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Login customer immediately
        |--------------------------------------------------------------------------
        |
        | They remain restricted from account features by Laravel's
        | "verified" middleware until they verify the email.
        |--------------------------------------------------------------------------
        */
        Auth::login(
            $user
        );


        $request
            ->session()
            ->regenerate();


        /*
        |--------------------------------------------------------------------------
        | Send verification email
        |--------------------------------------------------------------------------
        |
        | Your EventServiceProvider already connects Registered to
        | SendEmailVerificationNotification.
        |--------------------------------------------------------------------------
        */
        try {

            event(
                new Registered(
                    $user
                )
            );

        }
        catch (\Throwable $exception) {

            report(
                $exception
            );


            /*
            |--------------------------------------------------------------------------
            | Do not destroy the newly-created account just because
            | SMTP is temporarily unavailable.
            |--------------------------------------------------------------------------
            */
            return redirect()
                ->route(
                    'verification.notice'
                )
                ->withErrors([
                    'email' =>
                        'Your account was created, but the verification email could not be sent. Please use the resend button after checking the mail configuration.',
                ]);

        }


        return redirect()
            ->route(
                'verification.notice'
            )
            ->with(
                'success',
                'Account created. We sent a verification link to your email address.'
            );
    }
}