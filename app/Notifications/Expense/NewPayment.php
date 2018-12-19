<?php

namespace App\Notifications\Expense;

use App\Models\Expense\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;


class NewPayment extends Notification
{
    use Queueable;

    private $payment;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Expense\Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
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

        $url = url('/expenses/payments/'.$this->payment->id.'/edit');

        if ($this->payment->description) {
            return (new SlackMessage)
                ->error()
                ->content('Kassenupdate: Neue Ausgabe hinzugefügt!')
                ->attachment(function ($attachment) use ($url) {
                    $attachment->title('Eintrag Bearbeiten', $url)
                        ->fields([
                            'Kreditor' => $this->payment->vendor->name,
                            'Betrag' => $this->payment->amount .'€',
                            'Beschreibung' => $this->payment->description,
                            'Kategorie' => $this->payment->category->name,
                            'Kassenstand' => $this->payment->account->getBalanceAttribute() .'€',
                        ]);
                });
        } else {
            return (new SlackMessage)
                ->error()
                ->content('Kassenupdate: Neue Ausgabe hinzugefügt!')
                ->attachment(function ($attachment) use ($url) {
                    $attachment->title('Eintrag Bearbeiten', $url)
                        ->fields([
                            'Kreditor'     => $this->payment->vendor->name,
                            'Betrag'       => $this->payment->amount . '€',
                            'Kategorie'    => $this->payment->category->name,
                            'Kassenstand'  => $this->payment->account->getBalanceAttribute() . '€',
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
