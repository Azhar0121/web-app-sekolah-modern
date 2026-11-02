<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(public string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        $expire = config('auth.passwords.users.expire', 60);

        return (new MailMessage)
            ->subject('Permintaan Reset Password - ' . config('app.name'))
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Kami menerima permintaan untuk mengatur ulang kata sandi (password) akun Anda di portal ' . config('app.name') . '.')
            ->action('Reset Password Sekarang', $url)
            ->line("Tautan reset password ini hanya berlaku selama {$expire} menit.")
            ->line('Jika Anda tidak merasa mengajukan permintaan ini, silakan abaikan email ini. Kata sandi akun Anda tidak akan berubah.')
            ->salutation('Salam hangat,' . "\n" . 'Tim IT ' . config('app.name'));
    }
}
