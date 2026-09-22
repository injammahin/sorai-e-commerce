<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends BaseVerifyEmail
{
    use Queueable;

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verify your email address | SARAI')
            ->view('emails.auth.verify-email', [
                'user' => $notifiable,

                'verificationUrl' =>
                    $this->verificationUrl(
                        $notifiable
                    ),

                'expiresInMinutes' =>
                    (int) config(
                        'auth.verification.expire',
                        60
                    ),
            ]);
    }

    protected function verificationUrl(
        $notifiable
    ) {
        return URL::temporarySignedRoute(
            'verification.verify',

            now()->addMinutes(
                (int) config(
                    'auth.verification.expire',
                    60
                )
            ),

            [
                'id' =>
                    $notifiable->getKey(),

                'hash' =>
                    sha1(
                        $notifiable
                            ->getEmailForVerification()
                    ),
            ]
        );
    }
}