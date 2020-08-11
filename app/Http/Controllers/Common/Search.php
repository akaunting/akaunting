<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Events\Common\GlobalSearched;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
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
        $search = new \stdClass();
        $search->results = [];
        $search->keyword = request('keyword');

        $accounts = Account::enabled()->usingSearchString($search->keyword)->get();

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

        $items = Item::enabled()->usingSearchString($search->keyword)->get();

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

        $invoices = Invoice::usingSearchString($search->keyword)->get();

        if ($invoices->count()) {
            foreach ($invoices as $invoice) {
                $search->results[] = (object) [
                    'id'    => $invoice->id,
                    'name'  => $invoice->invoice_number . ' - ' . $invoice->contact_name,
                    'type'  => trans_choice('general.invoices', 1),
                    'color' => '#6da252',
                    'href'  => route('invoices.show', $invoice->id),
                ];
            }
        }

        /*
        $income_transactions = Transaction::income()->usingSearchString($keyword)->get();

        if ($income_transactions->count()) {
            foreach ($income_transactions as $transaction) {
                $results[] = (object)[
                    'id'    => $transaction->id,
                    'name'  => $transaction->contact_name,
                    'type'  => trans_choice('general.revenues', 1),
                    'color' => '#00c0ef',
                    'href'  => url('sales/revenues/' . $transaction->id),
                ];
            }
        }
        */

        $customers = Contact::customer()->enabled()->usingSearchString($search->keyword)->get();

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

        $bills = Bill::usingSearchString($search->keyword)->get();

        if ($bills->count()) {
            foreach ($bills as $bill) {
                $search->results[] = (object) [
                    'id'    => $bill->id,
                    'name'  => $bill->bill_number . ' - ' . $bill->contact_name,
                    'type'  => trans_choice('general.bills', 1),
                    'color' => '#ef3232',
                    'href'  => route('bills.show', $bill->id),
                ];
            }
        }

        /*
        $payments = Transaction::expense()->usingSearchString($keyword)->get();

        if ($revenues->count()) {
            foreach ($revenues as $revenue) {
                $results[] = (object)[
                    'id'    => $revenue->id,
                    'name'  => $revenue->contact_name,
                    'type'  => trans_choice('general.revenues', 1),
                    'color' => '#00c0ef',
                    'href'  => url('sales/revenues/' . $revenue->id),
                ];
            }
        }
        */

        $vendors = Contact::vendor()->enabled()->usingSearchString($search->keyword)->get();

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

        event(new GlobalSearched($search));

        return response()->json((object) $search->results);
    }
}
