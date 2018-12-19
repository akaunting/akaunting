<?php

namespace App\Notifications\Income;

use App\Models\Income\Revenue;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;


class NewRevenue extends Notification
{
    use Queueable;

    private $revenue;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Income\Revenue $revenue
     */
    public function __construct(Revenue $revenue)
    {
        $this->revenue = $revenue;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {

        $url = url('/incomes/revenues/'.$this->revenue->id.'/edit');

        if ($this->revenue->description) {
            return (new SlackMessage)
                ->success()
                ->content('Kassenupdate: Neue Einnahme hinzugefügt!')
                ->attachment(function ($attachment) use ($url) {
                    $attachment->title('Eintrag Bearbeiten', $url)
                        ->fields([
                            'Kunde' => $this->revenue->customer->name,
                            'Betrag' => $this->revenue->amount .'€',
                            'Beschreibung' => $this->revenue->description,
                            'Kategorie' => $this->revenue->category->name,
                            'Kassenstand' => $this->revenue->account->getBalanceAttribute() .'€',
                        ]);
                });
        } else {
            return (new SlackMessage)
                ->success()
                ->content('Kassenupdate: Neue Einnahme hinzugefügt!')
                ->attachment(function ($attachment) use ($url) {
                    $attachment->title('Eintrag Bearbeiten', $url)
                        ->fields([
                            'Kunde' => $this->revenue->customer->name,
                            'Betrag' => $this->revenue->amount .'€',
                            'Kategorie' => $this->revenue->category->name,
                            'Kassenstand' => $this->revenue->account->getBalanceAttribute() .'€',
                        ]);
                });
        }

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
            //
        ];
    }
}
