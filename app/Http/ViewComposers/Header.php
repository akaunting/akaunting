<?php

namespace App\Http\ViewComposers;

use App\Traits\Modules;
use App\Utilities\Versions;
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

        $new_apps = $invoices = $bills = $exports = $imports = [];
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
                        case 'App\Notifications\Common\ExportCompleted':
                            $exports['completed'][$data['file_name']] = $data['download_url'];
                            $notifications++;
                            break;
                        case 'App\Notifications\Common\ExportFailed':
                            $exports['failed'][] = $data['message'];
                            $notifications++;
                            break;
                        case 'App\Notifications\Common\ImportCompleted':
                            $imports['completed'][] = $data['translation'];
                            $notifications++;
                            break;
                        case 'App\Notifications\Common\ImportFailed':
                            $imports['failed'][] = '';
                            $notifications++;
                            break;
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

                $new_apps = $this->getNotifications('new-apps');

                foreach ($new_apps as $key => $new_app) {
                    if (setting('notifications.' . user()->id . '.' . $new_app->alias)) {
                        unset($new_apps[$key]);

                        continue;
                    }

                    $notifications++;
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
            'new_apps' => $new_apps,
            'exports' => $exports,
            'imports' => $imports,
            'bills' => $bills,
            'invoices' => $invoices,
            'company' => $company,
            'updates' => $updates,
        ]);
    }
}
