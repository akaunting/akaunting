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
        $pattern = $this->getTagsPattern();
        $replacement = $this->applyQuote($this->getTagsReplacement());

        return $this->revertQuote(preg_replace($pattern, $replacement, $content));
    }

    public function getTagsPattern()
    {
        $pattern = [];

        foreach($this->getTags() as $tag) {
            $pattern[] = "/" . $tag . "/";
        }

        return $pattern;
    }

    public function getTags()
    {
        return [];
    }

    public function getTagsReplacement()
    {
        return [];
    }

    public function applyQuote($vars)
    {
        $new_vars = [];

        foreach ($vars as $var) {
            $new_vars[] = preg_quote($var);
        }

        return $new_vars;
    }

    public function revertQuote($content)
    {
        return str_replace('\\', '', $content);
    }
}
