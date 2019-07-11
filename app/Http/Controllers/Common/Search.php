<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Banking\Account;
use App\Models\Expense\Bill;
use App\Models\Expense\Payment;
use App\Models\Expense\Vendor;
use App\Models\Income\Invoice;
use App\Models\Income\Revenue;
use App\Models\Income\Customer;
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
        $items = Item::enabled()->with('category')->get()->sortBy('name');

        return view('items.items.index', compact('items'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function search()
    {
        $results = array();

        $keyword = request('keyword');

        $accounts = Account::enabled()->search($keyword)->get();

        if ($accounts->count()) {
            foreach ($accounts as $account) {
                $results[] = (object)[
                    'id'    => $account->id,
                    'name'  => $account->name,
                    'type'  => trans_choice('general.accounts', 1),
                    'color' => '#337ab7',
                    'href'  => url('banking/accounts/' . $account->id . '/edit'),
                ];
            }
        }

        $items = Item::enabled()->search($keyword)->get();

        if ($items->count()) {
            foreach ($items as $item) {
                $results[] = (object)[
                    'id'    => $item->id,
                    'name'  => $item->name,
                    'type'  => trans_choice('general.items', 1),
                    'color' => '#f5bd65',
                    'href'  => url('common/items/' . $item->id . '/edit'),
                ];
            }
        }

        $invoices = Invoice::search($keyword)->get();

        if ($invoices->count()) {
            foreach ($invoices as $invoice) {
                $results[] = (object)[
                    'id'    => $invoice->id,
                    'name'  => $invoice->invoice_number . ' - ' . $invoice->customer_name,
                    'type'  => trans_choice('general.invoices', 1),
                    'color' => '#00c0ef',
                    'href'  => url('incomes/invoices/' . $invoice->id),
                ];
            }
        }

        //$revenues = Revenue::search($keyword)->get();

        $customers = Customer::enabled()->search($keyword)->get();

        if ($customers->count()) {
            foreach ($customers as $customer) {
                $results[] = (object)[
                    'id'    => $customer->id,
                    'name'  => $customer->name,
                    'type'  => trans_choice('general.customers', 1),
                    'color' => '#03d876',
                    'href'  => url('incomes/customers/' . $customer->id),
                ];
            }
        }

        $bills = Bill::search($keyword)->get();

        if ($bills->count()) {
            foreach ($bills as $bill) {
                $results[] = (object)[
                    'id'    => $bill->id,
                    'name'  => $bill->bill_number . ' - ' . $bill->vendor_name,
                    'type'  => trans_choice('general.bills', 1),
                    'color' => '#dd4b39',
                    'href'  => url('expenses/bills/' . $bill->id),
                ];
            }
        }

        //$payments = Payment::search($keyword)->get();

        $vendors = Vendor::enabled()->search($keyword)->get();

        if ($vendors->count()) {
            foreach ($vendors as $vendor) {
                $results[] = (object)[
                    'id'    => $vendor->id,
                    'name'  => $vendor->name,
                    'type'  => trans_choice('general.vendors', 1),
                    'color' => '#ff8373',
                    'href'  => url('expenses/vendors/' . $vendor->id),
                ];
            }
        }

        return response()->json((object) $results);
    }
}
