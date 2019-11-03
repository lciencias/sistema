<?php

namespace sistema\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;


class MyResetPassword extends ResetPassword
{
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
    	return (new MailMessage)
    	->subject('Recuperar contraseña')
    	->greeting('Estimado Usuario')
    	->line('Estás recibiendo este correo porque hiciste una solicitud de recuperación de contraseña para tu cuenta.')
    	->action('Recuperar contraseña', route('password.reset', $this->token))
    	->line('Si no realizaste esta solicitud, no se requiere realizar ninguna otra acción.')
    	->salutation('Saludos. ');
    }   
}
