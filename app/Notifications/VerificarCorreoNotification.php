<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Auth\Notifications\VerifyEmail;

class VerificarCorreoNotification extends VerifyEmail
{
    use Queueable;

    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl(
            $notifiable
        );

        return (new MailMessage)

            ->subject(
                'Verifica tu cuenta LevaDesk'
            )

            ->greeting(
                'Hola '.$notifiable->name
            )

            ->line(
                'Gracias por registrarte en LevaDesk.'
            )

            ->line(
                'Para activar tu cuenta debes verificar tu correo corporativo.'
            )

            ->action(
                'Verificar cuenta',
                $verificationUrl
            )

            ->line(
                'Si no creaste esta cuenta, puedes ignorar este correo.'
            )

            ->salutation(
                'Equipo LevaDesk'
            );
    }
}