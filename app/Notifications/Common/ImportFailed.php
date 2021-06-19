<?php

namespace App\Notifications\Common;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ImportFailed extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The error messages.
     *
     * @var array
     */
    public $errors;

    /**
     * Create a notification instance.
     *
     * @param  object  $errors
     */
    public function __construct($errors)
    {
        $this->errors = $errors;

        $this->onQueue('notifications');
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject(trans('notifications.import.failed.subject'))
            ->line(trans('notifications.import.failed.description'));

        foreach ($this->errors as $error) {
            $message->line($error);
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'errors' => $this->errors,
        ];
    }
}
