<?php

namespace App\Notifications\Common;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class DownloadFailed extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The error exception.
     *
     * @var string
     */
    public $message;

    /**
     * Create a notification instance.
     *
     * @param  string  $message
     */
    public function __construct($message)
    {
        $this->message = $message;

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
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(trans('notifications.download.failed.title'))
            ->line(new HtmlString('<br><br>'))
            ->line(trans('notifications.download.failed.description'))
            ->line(new HtmlString('<br><br>'))
            ->line($this->message)
            ->line(new HtmlString('<br><br>'));
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
            'title' => trans('notifications.menu.download_failed.title'),
            'description' => trans('notifications.menu.download_failed.description'),
            'message' => $this->message,
        ];
    }
}
