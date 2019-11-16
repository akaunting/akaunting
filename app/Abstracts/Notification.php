<?php

namespace App\Abstracts;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as BaseNotification;

abstract class Notification extends BaseNotification
{
    /**
     * Create a notification instance.
     */
    public function __construct()
    {
        $this->queue = 'high';
        $this->delay = config('queue.connections.database.delay');
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
     * Initialise the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function initMessage()
    {
        $message = (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($this->getSubject())
            ->view('partials.email.body', ['body' => $this->getBody()]);

        return $message;
    }

    public function getSubject()
    {
        $content = setting('email.' . $this->template . '_subject');

        return $this->replaceTags($content);
    }

    public function getBody()
    {
        $content = setting('email.' . $this->template . '_body');

        return $this->replaceTags($content);
    }

    public function replaceTags($content)
    {
        return preg_replace($this->getTagsPattern(), $this->getTagsReplacement(), $content);
    }

    public function getTagsPattern()
    {
        $pattern = [];

        foreach($this->getTags() as $tag) {
            $pattern[] = "/" . $tag . "/";
        }

        return $pattern;
    }
}
