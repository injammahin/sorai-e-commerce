<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create(
        Request $request
    ) {
        return view(
            'auth.login',
            [
                'isAdminLogin' =>
                    $request->routeIs(
                        'admin.login'
                    ),
            ]
        );
    }


    public function store(
        Request $request
    ) {
        $credentials =
            $request->validate([
                'email' => [
                    'required',
                    'email',
                ],

                'password' => [
                    'required',
                    'string',
                ],
            ]);


        /*
        |--------------------------------------------------------------------------
        | Authenticate
        |--------------------------------------------------------------------------
        */
        if (
            ! Auth::attempt(
                $credentials,
                $request->boolean(
                    'remember'
                )
            )
        ) {
            return back()
                ->withErrors([
                    'email' =>
                        'These credentials do not match our records.',
                ])
                ->onlyInput(
                    'email'
                );
        }


        $request
            ->session()
            ->regenerate();


        $user =
            $request->user();


        /*
        |--------------------------------------------------------------------------
        | Disabled account protection
        |--------------------------------------------------------------------------
        |
        | Important: log the account out again instead of leaving a disabled
        | account authenticated after throwing a 403.
        |--------------------------------------------------------------------------
        */
        if (
            ! $user->is_active
        ) {
            Auth::logout();


            $request
                ->session()
                ->invalidate();


            $request
                ->session()
                ->regenerateToken();


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
        | Customer email verification
        |--------------------------------------------------------------------------
        */
        if (
            ! $user->isAdmin()
            &&
            ! $user->hasVerifiedEmail()
        ) {
            return redirect()
                ->route(
                    'verification.notice'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->intended(
                $user->isAdmin()

                    ? route(
                        'admin.dashboard'
                    )

                    : route(
                        'account.index'
                    )
            );
    }


    public function destroy(
        Request $request
    ) {
        Auth::logout();


        $request
            ->session()
            ->invalidate();


        $request
            ->session()
            ->regenerateToken();


        return redirect()
            ->route(
                'home'
            );
    }
}