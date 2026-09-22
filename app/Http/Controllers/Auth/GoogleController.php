<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        /*
        |--------------------------------------------------------------------------
        | Google configuration must exist
        |--------------------------------------------------------------------------
        */
        abort_unless(

            config(
                'services.google.client_id'
            )

            &&

            config(
                'services.google.client_secret'
            )

            &&

            config(
                'services.google.redirect'
            ),

            503,

            'Google sign-in is not configured.'

        );


        return Socialite::driver(
            'google'
        )
            ->redirect();
    }


    public function callback()
    {
        /*
        |--------------------------------------------------------------------------
        | Retrieve Google user
        |--------------------------------------------------------------------------
        */
        try {

            $googleUser =
                Socialite::driver(
                    'google'
                )
                    ->user();

        }
        catch (\Throwable $exception) {

            report(
                $exception
            );


            return redirect()
                ->route(
                    'login'
                )
                ->withErrors([
                    'email' =>
                        'Google sign-in could not be completed. Please try again.',
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Google email
        |--------------------------------------------------------------------------
        */
        $googleEmail =
            strtolower(
                trim(
                    (string)
                    $googleUser
                        ->getEmail()
                )
            );


        if (
            $googleEmail
            ===
            ''
        ) {
            return redirect()
                ->route(
                    'login'
                )
                ->withErrors([
                    'email' =>
                        'Google did not provide an email address for this account.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Existing user
        |--------------------------------------------------------------------------
        |
        | Match either the Google ID or verified Google email.
        |--------------------------------------------------------------------------
        */
        $user =
            User::query()
                ->where(
                    'google_id',
                    $googleUser->getId()
                )
                ->orWhere(
                    'email',
                    $googleEmail
                )
                ->first();


        /*
        |--------------------------------------------------------------------------
        | Disabled customer
        |--------------------------------------------------------------------------
        */
        if (
            $user
            &&
            ! $user->is_active
        ) {
            return redirect()
                ->route(
                    'login'
                )
                ->withErrors([
                    'email' =>
                        'Your account is currently disabled.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Do not allow customer Google flow to become admin authentication
        |--------------------------------------------------------------------------
        */
        if (
            $user
            &&
            $user->isAdmin()
        ) {
            return redirect()
                ->route(
                    'admin.login'
                )
                ->withErrors([
                    'email' =>
                        'Administrator accounts must sign in with the admin login form.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Existing customer
        |--------------------------------------------------------------------------
        */
        if ($user) {

            $user
                ->forceFill([
                    'google_id' =>
                        $googleUser->getId(),

                    'avatar' =>
                        $user->avatar
                        ?:
                        $googleUser->getAvatar(),

                    /*
                    |--------------------------------------------------------------------------
                    | Google-authenticated email is treated as verified.
                    |--------------------------------------------------------------------------
                    */
                    'email_verified_at' =>
                        $user->email_verified_at
                        ?:
                        now(),
                ])
                ->save();

        }

        /*
        |--------------------------------------------------------------------------
        | New customer
        |--------------------------------------------------------------------------
        */
        else {

            $user =
                User::create([
                    'name' =>
                        $googleUser->getName()
                        ?:
                        'SARAI Customer',

                    'email' =>
                        $googleEmail,

                    'google_id' =>
                        $googleUser->getId(),

                    'avatar' =>
                        $googleUser->getAvatar(),

                    'email_verified_at' =>
                        now(),

                    /*
                    |--------------------------------------------------------------------------
                    | Password is nullable in your existing users migration.
                    |--------------------------------------------------------------------------
                    */
                    'password' =>
                        null,

                    'role' =>
                        'customer',

                    'is_active' =>
                        true,
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Login
        |--------------------------------------------------------------------------
        */
        Auth::login(
            $user
        );


        request()
            ->session()
            ->regenerate();


        return redirect()
            ->intended(
                route(
                    'account.index'
                )
            );
    }
}