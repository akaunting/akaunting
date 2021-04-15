<?php

namespace App\Abstracts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as BaseNotification;

abstract class Notification extends BaseNotification implements ShouldQueue
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
        return ['mail', 'database'];
    }

    /**
     * Initialise the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function initMessage()
    {
        app('url')->defaults(['company_id' => company_id()]);

        $message = (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($this->getSubject())
            ->view('partials.email.body', ['body' => $this->getBody()]);

        return $message;
    }

    public function getSubject()
    {
        return $this->replaceTags($this->template->subject);
    }

    public function getBody()
    {
        return $this->replaceTags($this->template->body);
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
