<?php

namespace App\Http\ViewComposers;

use App\Utilities\Versions;
use App\Traits\Modules;
use Illuminate\View\View;

class Header
{
    use Modules;

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

            if ($user->can('read-common-notifications')) {
                $unreads = $user->unreadNotifications;

                foreach ($unreads as $unread) {
                    $data = $unread->getAttribute('data');

                    switch ($unread->getAttribute('type')) {
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
            }

            if ($user->can('read-install-updates')) {
                $updates = count(Versions::getUpdates());
            }

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
