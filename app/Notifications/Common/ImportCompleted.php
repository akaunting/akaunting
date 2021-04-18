<?php

namespace App\Notifications\Common;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ImportCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a notification instance.
     */
    public function __construct()
    {
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
        $dashboard_url = route('dashboard', ['company_id' => company_id()]);

        return (new MailMessage)
            ->subject(trans('notifications.import.completed.subject'))
            ->line(trans('notifications.import.completed.description'))
            ->action(trans_choice('general.dashboards', 1), $dashboard_url);
    }
}
