<?php

namespace App\Http\ViewComposers;

use Auth;
use App\Utilities\Updater;
use Illuminate\View\View;
use App\Traits\Modules as RemoteModules;

class Header
{
    use RemoteModules;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $user = Auth::user();

        $bills = [];
        $invoices = [];
        $items = [];
        $items_reminder = [];
        $notifications = 0;
        $company = null;

        // Get customer company
        if ($user->customer()) {
            $company = (object)[
                'company_name' => setting('general.company_name'),
                'company_email' => setting('general.company_email'),
                'company_address' => setting('general.company_address'),
                'company_logo' => setting('general.company_logo'),
            ];
        }

        $undereads = $user->unreadNotifications;

        foreach ($undereads as $underead) {
            $data = $underead->getAttribute('data');

            switch ($underead->getAttribute('type')) {
                case 'App\Notifications\Expense\Bill':
                    $bills[$data['bill_id']] = $data['amount'];
                    $notifications++;
                    break;
                case 'App\Notifications\Income\Invoice':
                    $invoices[$data['invoice_id']] = $data['amount'];
                    $notifications++;
                    break;
                case 'App\Notifications\Common\Item':
                    $items[$data['item_id']] = $data['name'];
                    $notifications++;
                    break;
                case 'App\Notifications\Common\ItemReminder':
                    $items_reminder[$data['item_id']] = $data['name'];
                    $notifications++;
                    break;
            }
        }

        $updates = count(Updater::all());

        $this->loadSuggestions();

        $view->with([
            'user' => $user,
            'notifications' => $notifications,
            'bills' => $bills,
            'invoices' => $invoices,
            'items' => $items,
            'items_reminder' => $items_reminder,
            'company' => $company,
            'updates' => $updates,
        ]);
    }
}
