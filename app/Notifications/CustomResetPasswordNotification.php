<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail(object $notifiable)
    {
        $resetUrl = url(route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false));

        return (new MailMessage)
            ->subject('Permintaan Reset Kata Sandi')
            ->line('Kami menerima permintaan untuk mengatur ulang kata sandi Anda. Jika Anda tidak melakukan permintaan ini, Anda bisa mengabaikan email ini.')
            ->line('Untuk mengatur ulang kata sandi, klik tombol di bawah ini:')
            ->action('Reset Password', $resetUrl)
            ->line('Link reset akan kedaluwarsa dalam waktu 60 menit.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
