<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Events\Common\GlobalSearched;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Models\Common\Item;

class Search extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user = user();

        $search = new \stdClass();
        $search->results = [];
        $search->keyword = request('keyword');

        if ($user->can('read-banking-accounts')) {
            $accounts = Account::enabled()->usingSearchString($search->keyword)->take(setting('default.select_limit'))->get();

            if ($accounts->count()) {
                foreach ($accounts as $account) {
                    $search->results[] = (object) [
                        'id'    => $account->id,
                        'name'  => $account->name,
                        'type'  => trans_choice('general.accounts', 1),
                        'color' => '#55588b',
                        'href'  => route('accounts.edit', $account->id),
                    ];
                }
            }
        }

        if ($user->can('read-common-items')) {
            $items = Item::enabled()->usingSearchString($search->keyword)->take(setting('default.select_limit'))->get();

            if ($items->count()) {
                foreach ($items as $item) {
                    $search->results[] = (object) [
                        'id'    => $item->id,
                        'name'  => $item->name,
                        'type'  => trans_choice('general.items', 1),
                        'color' => '#efad32',
                        'href'  => route('items.edit', $item->id),
                    ];
                }
            }
        }

        if ($user->can('read-sales-invoices')) {
            $invoices = Document::invoice()->usingSearchString($search->keyword)->take(setting('default.select_limit'))->get();

            if ($invoices->count()) {
                foreach ($invoices as $invoice) {
                    $search->results[] = (object) [
                        'id'    => $invoice->id,
                        'name'  => $invoice->document_number . ' - ' . $invoice->contact_name,
                        'type'  => trans_choice('general.invoices', 1),
                        'color' => '#6da252',
                        'href'  => route('invoices.show', $invoice->id),
                    ];
                }
            }
        }

        /*if ($user->can('read-sales-revenues')) {
            $revenues = Transaction::income()->usingSearchString($search->keyword)->take(setting('default.select_limit'))->get();

            if ($revenues->count()) {
                foreach ($revenues as $revenue) {
                    $results[] = (object)[
                        'id'    => $revenue->id,
                        'name'  => $revenue->contact_name,
                        'type'  => trans_choice('general.revenues', 1),
                        'color' => '#00c0ef',
                        'href'  => route('revenues.edit', $revenue->id),
                    ];
                }
            }
        }*/

        if ($user->can('read-sales-customers')) {
            $customers = Contact::customer()->enabled()->usingSearchString($search->keyword)->take(setting('default.select_limit'))->get();

            if ($customers->count()) {
                foreach ($customers as $customer) {
                    $search->results[] = (object) [
                        'id'    => $customer->id,
                        'name'  => $customer->name,
                        'type'  => trans_choice('general.customers', 1),
                        'color' => '#328aef',
                        'href'  => route('customers.show', $customer->id),
                    ];
                }
            }
        }

        if ($user->can('read-purchases-bills')) {
            $bills = Document::bill()->usingSearchString($search->keyword)->take(setting('default.select_limit'))->get();

            if ($bills->count()) {
                foreach ($bills as $bill) {
                    $search->results[] = (object) [
                        'id'    => $bill->id,
                        'name'  => $bill->document_number . ' - ' . $bill->contact_name,
                        'type'  => trans_choice('general.bills', 1),
                        'color' => '#ef3232',
                        'href'  => route('bills.show', $bill->id),
                    ];
                }
            }
        }

        /*if ($user->can('read-purchases-payments')) {
            $payments = Transaction::expense()->usingSearchString($search->keyword)->take(setting('default.select_limit'))->get();

            if ($payments->count()) {
                foreach ($payments as $payment) {
                    $results[] = (object)[
                        'id'    => $payment->id,
                        'name'  => $payment->contact_name,
                        'type'  => trans_choice('general.payments', 1),
                        'color' => '#00c0ef',
                        'href'  => route('payments.edit', $payment->id),
                    ];
                }
            }
        }*/

        if ($user->can('read-purchases-vendors')) {
            $vendors = Contact::vendor()->enabled()->usingSearchString($search->keyword)->take(setting('default.select_limit'))->get();

            if ($vendors->count()) {
                foreach ($vendors as $vendor) {
                    $search->results[] = (object) [
                        'id'    => $vendor->id,
                        'name'  => $vendor->name,
                        'type'  => trans_choice('general.vendors', 1),
                        'color' => '#efef32',
                        'href'  => route('vendors.show', $vendor->id),
                    ];
                }
            }
        }

        event(new GlobalSearched($search));

        return response()->json((object) $search->results);
    }
}
