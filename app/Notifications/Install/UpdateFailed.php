<?php

namespace App\Notifications\Install;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class UpdateFailed extends Notification
{
    /**
     * The event.
     *
     * @var object
     */
    public $event;

    /**
     * The notification config.
     *
     * @var object
     */
    public $notifications;

    /**
     * Create a notification instance.
     *
     * @param  object  $event
     */
    public function __construct($event)
    {
        $this->event = $event;
        $this->notifications = config('update.notifications');
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        $channels = [];

        foreach ($this->notifications as $channel => $settings) {
            if (empty($settings['enabled'])) {
                continue;
            }

            $channels[] = $channel;
        }

        return $channels;
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = trans('notifications.update.mail.subject', [
            'domain' => request()->getHttpHost(),
        ]);

        $message = trans('notifications.update.mail.message', [
            'alias'             => $this->getAliasName(),
            'current_version'   => $this->event->old,
            'new_version'       => $this->event->new,
            'step'              => $this->event->step,
            'error_message'     => $this->event->message,
        ]);

        return (new MailMessage)
            ->from($this->notifications['mail']['from'], $this->notifications['mail']['name'])
            ->subject($subject)
            ->line($message);
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $message = trans('notifications.update.slack.message', [
            'domain' => request()->getHttpHost(),
        ]);

        return (new SlackMessage)
            ->error()
            ->from($this->notifications['slack']['from'], $this->notifications['slack']['emoji'])
            ->to($this->notifications['slack']['channel'])
            ->content($message)
            ->attachment(function ($attachment) {
                $attachment->fields([
                    'Alias'             => $this->getAliasName(),
                    'Current Version'   => $this->event->old,
                    'New Version'       => $this->event->new,
                    'Step'              => $this->event->step,
                    'Error Message'     => $this->event->message,
                ]);
            });
    }

    protected function getAliasName()
    {
        if ($this->event->alias == 'core') {
            return config('app.name');
        }

        $module = module($this->event->alias);

        if (empty($module)) {
            return ucfirst($this->event->alias);
        }

        return $module->getName();
    }
}
