<?php

namespace App\Abstracts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\Str;

abstract class Notification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Custom mail subject, body, etc.
     *
     * @var array
     */
    public $custom_mail;

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
     */
    public function initMailMessage(): MailMessage
    {
        app('url')->defaults(['company_id' => company_id()]);

        $message = (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($this->getSubject())
            ->view('components.email.body', ['body' => $this->getBody()]);

        if (!empty($this->custom_mail['cc'])) {
            $message->cc($this->custom_mail['cc']);
        }
        
        if (!empty($this->custom_mail['bcc'])) {
            $message->bcc($this->custom_mail['bcc']);
        }

        return $message;
    }

    /**
     * Initialise the array representation of the notification.
     *
     */
    public function initArrayMessage(): void
    {
        app('url')->defaults(['company_id' => company_id()]);
    }

    public function getSubject(): string
    {
        return !empty($this->custom_mail['subject'])
                ? $this->custom_mail['subject']
                : $this->replaceTags($this->template->subject);
    }

    public function getBody()
    {
        $body = !empty($this->custom_mail['body']) ? $this->custom_mail['body'] : $this->replaceTags($this->template->body);

        return $body . $this->getFooter();
    }

    public function replaceTags(string $content): string
    {
        $pattern = $this->getTagsPattern();
        $replacement = $this->applyQuote($this->getTagsReplacement());

        return $this->revertQuote(preg_replace($pattern, $replacement, $content));
    }

    public function getFooter()
    {
        $url = 'https://akaunting.com/accounting-software?utm_source=email&utm_medium=footer&utm_campaign=plg&utm_content=' . $this->template->alias;

        $get_started = '<a href="' . $url . '" style="color: #676ba2; text-decoration: none;">' . trans('footer.get_started') . '</a>';

        return view('components.email.footer', compact('url', 'get_started'));
    }

    public function getTagsPattern(): array
    {
        $pattern = [];

        foreach($this->getTags() as $tag) {
            $pattern[] = "/" . $tag . "/";
        }

        return $pattern;
    }

    public function getTags(): array
    {
        return [];
    }

    public function getTagsReplacement(): array
    {
        return [];
    }

    public function getTagsBinding(): array
    {
        $bindings = [];

        $tags = $this->getTags();
        $replacements = $this->getTagsReplacement();

        $wrappers = ['{', '}'];

        foreach ($tags as $index => $tag) {
            $key = Str::replace($wrappers, '', $tag);

            $bindings[$key] = $replacements[$index];
        }

        return $bindings;
    }

    public function applyQuote(array $vars): array
    {
        $new_vars = [];

        foreach ($vars as $var) {
            // Ensure $var is a string, default to an empty string if it is null
            $new_vars[] = preg_quote($var ?? '');
        }

        return $new_vars;
    }

    public function revertQuote(string $content): string
    {
        return str_replace('\\', '', $content);
    }

    /**
     * @deprecated 3.0
     */
    public function initMessage()
    {
        return $this->initMailMessage();
    }
}
