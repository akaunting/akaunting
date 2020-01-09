<?php

namespace App\Abstracts;

use App\Models\Common\EmailTemplate;
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
        $template = EmailTemplate::alias($this->template)->first();

        $message = (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($this->getSubject($template))
            ->view('partials.email.body', ['body' => $this->getBody($template)]);

        return $message;
    }

    public function getSubject($template)
    {
        return $this->replaceTags($template->subject);
    }

    public function getBody($template)
    {
        return $this->replaceTags($template->body);
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
