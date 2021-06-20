<?php

namespace App\Notifications\Common;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ImportCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $translation;

    protected $total_rows;

    /**
     * Create a notification instance.
     */
    public function __construct($translation, $total_rows)
    {
        $this->translation = $translation;
        $this->total_rows = $total_rows;

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
        $dashboard_url = route('dashboard', ['company_id' => company_id()]);

        return (new MailMessage)
            ->subject(trans('notifications.import.completed.subject'))
            ->line(trans('notifications.import.completed.description'))
            ->action(trans_choice('general.dashboards', 1), $dashboard_url);
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
            'translation' => $this->translation,
            'total_rows' => $this->total_rows,
        ];
    }
}
