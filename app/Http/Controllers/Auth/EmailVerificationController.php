<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function notice(
        Request $request
    ) {
        /*
        |--------------------------------------------------------------------------
        | Already verified
        |--------------------------------------------------------------------------
        */
        if (
            $request
                ->user()
                ->hasVerifiedEmail()
        ) {
            return redirect()
                ->route(
                    'account.index'
                );
        }


        return view(
            'auth.verify-email'
        );
    }


    public function verify(
        EmailVerificationRequest $request
    ) {
        /*
        |--------------------------------------------------------------------------
        | Verify only once
        |--------------------------------------------------------------------------
        */
        if (
            ! $request
                ->user()
                ->hasVerifiedEmail()
        ) {
            $request->fulfill();
        }


        return redirect()
            ->route(
                'account.index'
            )
            ->with(
                'success',
                'Your email address has been verified successfully.'
            );
    }


    public function send(
        Request $request
    ) {
        /*
        |--------------------------------------------------------------------------
        | Already verified
        |--------------------------------------------------------------------------
        */
        if (
            $request
                ->user()
                ->hasVerifiedEmail()
        ) {
            return redirect()
                ->route(
                    'account.index'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Resend
        |--------------------------------------------------------------------------
        */
        try {

            $request
                ->user()
                ->sendEmailVerificationNotification();

        }
        catch (\Throwable $exception) {

            report(
                $exception
            );


            return back()
                ->withErrors([
                    'email' =>
                        'We could not send the verification email right now. Please try again shortly.',
                ]);

        }


        return back()
            ->with(
                'success',
                'A new verification link has been sent to your email address.'
            );
    }
}