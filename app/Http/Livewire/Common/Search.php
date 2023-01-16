<?php

namespace App\Http\Livewire\Common;

use App\Events\Common\GlobalSearched;
use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Document\Document;
use Livewire\Component;

class Search extends Component
{
    public $user = null;

    public $keyword = '';

    public $results = [];

    protected $listeners = ['resetKeyword'];

    /**
     * Rendering component.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        $this->user = user();
        
        $this->search();

        return view('livewire.common.search');
    }

    /**
     * Searching operation.
     * If the keyword is empty searching will be cancelled.
     *
     * @return void
     */
    public function search()
    {
        $this->results = [];

        if (empty($this->keyword)) {
            return;
        }

        $this->searchOnAccounts();
        $this->searchOnItems();
        $this->searchOnInvoices();
        $this->searchOnCustomers();
        $this->searchOnBills();
        $this->searchOnVendors();

        $this->dispatchGlobalSearched();
    }

    /**
     * Resets keyword.
     *
     * @return void
     */
    public function resetKeyword()
    {
        $this->keyword = '';
    }

    /**
     * Fires GlobalSearched event with the keyword and results.
     *
     * @return void
     */
    public function dispatchGlobalSearched()
    {
        $search = new \stdClass();
        $search->results = $this->results;
        $search->keyword = $this->keyword;

        event(new GlobalSearched($search));

        $this->results = $search->results;
    }

    /**
     * Searching on Banking Accounts with given keyword.
     *
     * @return void
     */
    public function searchOnAccounts()
    {
        if (!$this->user->can('read-banking-accounts')) {
            return;
        }

        $accounts = Account::enabled()
            ->usingSearchString($this->keyword)
            ->take(setting('default.select_limit'))
            ->get();

        if ($accounts->isEmpty()) {
            return;
        }

        foreach ($accounts as $account) {
            $this->results[] = (object) [
                'id' => $account->id,
                'name' => $account->name,
                'type' => trans_choice('general.accounts', 1),
                'color' => '#55588b',
                'href' => route('accounts.show', $account->id),
            ];
        }
    }

    /**
     * Searching on Items with given keyword.
     *
     * @return void
     */
    public function searchOnItems()
    {
        if (!$this->user->can('read-common-items')) {
            return;
        }

        $items = Item::enabled()
            ->usingSearchString($this->keyword)
            ->take(setting('default.select_limit'))
            ->get();

        if ($items->isEmpty()) {
            return;
        }

        foreach ($items as $item) {
            $this->results[] = (object) [
                'id' => $item->id,
                'name' => $item->name,
                'type' => trans_choice('general.items', 1),
                'color' => '#efad32',
                'href' => route('items.edit', $item->id),
            ];
        }
    }

    /**
     * Searching on Invoices with given keyword.
     *
     * @return void
     */
    public function searchOnInvoices()
    {
        if (!$this->user->can('read-sales-invoices')) {
            return;
        }

        $invoices = Document::invoice()
            ->usingSearchString($this->keyword)
            ->take(setting('default.select_limit'))
            ->get();

        if ($invoices->isEmpty()) {
            return;
        }

        foreach ($invoices as $invoice) {
            $this->results[] = (object) [
                'id' => $invoice->id,
                'name' => $invoice->document_number . ' - ' . $invoice->contact_name,
                'type' => trans_choice('general.invoices', 1),
                'color' => '#6da252',
                'href' => route('invoices.show', $invoice->id),
            ];
        }
    }

    /**
     * Searching on Customers with given keyword.
     *
     * @return void
     */
    public function searchOnCustomers()
    {
        if (!$this->user->can('read-sales-customers')) {
            return;
        }

        $customers = Contact::customer()
            ->enabled()
            ->usingSearchString($this->keyword)
            ->take(setting('default.select_limit'))
            ->get();

        if ($customers->isEmpty()) {
            return;
        }

        foreach ($customers as $customer) {
            $this->results[] = (object) [
                'id' => $customer->id,
                'name' => $customer->name,
                'type' => trans_choice('general.customers', 1),
                'color' => '#328aef',
                'href' => route('customers.show', $customer->id),
            ];
        }
    }

    /**
     * Searching on Bills with given keyword.
     *
     * @return void
     */
    public function searchOnBills()
    {
        if (!$this->user->can('read-purchases-bills')) {
            return;
        }

        $bills = Document::bill()
            ->usingSearchString($this->keyword)
            ->take(setting('default.select_limit'))
            ->get();

        if ($bills->isEmpty()) {
            return;
        }

        foreach ($bills as $bill) {
            $this->results[] = (object) [
                'id' => $bill->id,
                'name' => $bill->document_number . ' - ' . $bill->contact_name,
                'type' => trans_choice('general.bills', 1),
                'color' => '#ef3232',
                'href' => route('bills.show', $bill->id),
            ];
        }
    }

    /**
     * Searching on Vendors with given keyword.
     *
     * @return void
     */
    public function searchOnVendors()
    {
        if (!$this->user->can('read-purchases-vendors')) {
            return;
        }

        $vendors = Contact::vendor()
            ->enabled()
            ->usingSearchString($this->keyword)
            ->take(setting('default.select_limit'))
            ->get();

        if ($vendors->isEmpty()) {
            return;
        }

        foreach ($vendors as $vendor) {
            $this->results[] = (object) [
                'id' => $vendor->id,
                'name' => $vendor->name,
                'type' => trans_choice('general.vendors', 1),
                'color' => '#efef32',
                'href' => route('vendors.show', $vendor->id),
            ];
        }
    }
}
