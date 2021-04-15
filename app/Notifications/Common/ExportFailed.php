<?php

namespace App\Notifications\Common;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ExportFailed extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The error exception.
     *
     * @var object
     */
    public $exception;

    /**
     * Create a notification instance.
     *
     * @param  object  $exception
     */
    public function __construct($exception)
    {
        $this->exception = $exception;

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
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(trans('notifications.export.failed.subject'))
            ->line(trans('notifications.export.failed.description'))
            ->line($this->exception->getMessage());
    }
}
