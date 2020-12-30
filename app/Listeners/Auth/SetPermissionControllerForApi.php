<?php

namespace App\Listeners\Auth;

use App\Events\Auth\ApiPermissionsAssigning as Event;

class SetPermissionControllerForApi
{
    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        // Not a common API endpoint
        if (!in_array($event->table, ['contacts', 'documents', 'transactions'])) {
            return;
        }

        // api/contacts?type=customer
        // api/contacts?type=vendor
        if ($event->table == 'contacts') {
            switch ($event->type) {
                case 'customer':
                    $event->permission->controller = 'sales-customers';
                    break;
                case 'vendor':
                    $event->permission->controller = 'purchases-vendors';
                    break;
            }
        }

        // api/documents?type=invoice
        // api/documents?type=bill
        if ($event->table == 'documents') {
            switch ($event->type) {
                case 'invoice':
                    $event->permission->controller = 'sales-invoices';
                    break;
                case 'bill':
                    $event->permission->controller = 'purchases-bills';
                    break;
            }
        }

        // api/transactions?type=income
        // api/transactions?type=expense
        if ($event->table == 'transactions') {
            switch ($event->type) {
                case 'income':
                    $event->permission->controller = 'sales-revenues';
                    break;
                case 'expense':
                    $event->permission->controller = 'purchases-payments';
                    break;
            }
        }
    }
}
