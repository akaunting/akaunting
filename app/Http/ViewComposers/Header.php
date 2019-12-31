<?php

namespace App\Http\ViewComposers;

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
        $user = user();

        $invoices = $bills = [];
        $updates = $notifications = 0;
        $company = null;

        if (!empty($user)) {
            // Get customer company
            if ($user->can('read-client-portal')) {
                $company = (object) [
                    'company_name' => setting('company.name'),
                    'company_email' => setting('company.email'),
                    'company_address' => setting('company.address'),
                    'company_logo' => setting('company.logo'),
                ];
            }

            $undereads = $user->unreadNotifications;

            foreach ($undereads as $underead) {
                $data = $underead->getAttribute('data');

                switch ($underead->getAttribute('type')) {
                    case 'App\Notifications\Purchase\Bill':
                        $bills[$data['bill_id']] = $data['amount'];
                        $notifications++;
                        break;
                    case 'App\Notifications\Sale\Invoice':
                        $invoices[$data['invoice_id']] = $data['amount'];
                        $notifications++;
                        break;
                }
            }

            $updates = count(Updater::all());

            $this->loadSuggestions();
        }

        $view->with([
            'user' => $user,
            'notifications' => $notifications,
            'bills' => $bills,
            'invoices' => $invoices,
            'company' => $company,
            'updates' => $updates,
        ]);
    }
}
