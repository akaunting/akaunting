<?php

namespace App\Notifications\Common;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class DownloadCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $translation;

    protected $file_name;

    protected $download_url;

    /**
     * Create a notification instance.
     *
     * @param  string  $download_url
     */
    public function __construct($translation, $file_name, $download_url)
    {
        $this->translation = $translation;
        $this->file_name = $file_name;
        $this->download_url = $download_url;

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
            ->subject(trans('notifications.download.completed.title'))
            ->line(new HtmlString('<br><br>'))
            ->line(trans('notifications.download.completed.description'))
            ->action(trans('general.download'), $this->download_url);
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
            'title' => trans('notifications.menu.download_completed.title'),
            'description' => trans('notifications.menu.download_completed.description', [
                'type'  => $this->translation,
                'url'   => $this->download_url,
            ]),
            'translation' => $this->translation,
            'file_name' => $this->file_name,
            'download_url' => $this->download_url,
        ];
    }
}
