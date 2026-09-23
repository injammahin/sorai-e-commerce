<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $successMessage = 'Thank you. We will reply shortly.';

        /*
        |--------------------------------------------------------------------------
        | Honeypot spam protection
        |--------------------------------------------------------------------------
        |
        | Real visitors leave "website" empty. If a bot fills it, return a
        | normal-looking success response without storing anything.
        |
        */
        if ($request->filled('website')) {
            return back()->with('success', $successMessage);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate only real database fields
        |--------------------------------------------------------------------------
        */
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:191',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:191',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'subject' => [
                'nullable',
                'string',
                'max:191',
            ],

            'message' => [
                'required',
                'string',
                'max:3000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Save message
        |--------------------------------------------------------------------------
        |
        | The website honeypot is not present in $data, so Laravel will not
        | try to insert it into the contact_messages table.
        |
        */
        ContactMessage::create($data);

        return back()->with('success', $successMessage);
    }

    public function subscribe(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:191',
            ],
        ]);

        $email = strtolower(trim($data['email']));

        Subscriber::updateOrCreate(
            [
                'email' => $email,
            ],
            [
                'is_active' => true,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]
        );

        return back()->with(
            'success',
            'Welcome to the AATCHALA journal.'
        );
    }
}