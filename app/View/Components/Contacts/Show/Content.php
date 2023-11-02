<?php

namespace App\View\Components\Contacts\Show;

use App\Utilities\Date;
use App\Abstracts\View\Components\Contacts\Show as Component;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class Content extends Component
{
    public $counts;

    public $summary_amounts;

    public $transactions;

    public $documents;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $totals = [
            'paid' => 0,
            'open' => 0,
            'overdue' => 0,
        ];

        $this->counts = [];

        // Handle documents
        $docs = $this->contact->isCustomer() ? 'invoices' : 'bills';

        $this->documents = $this->contact->$docs()->with('transactions')->get();

        $this->counts['documents'] = $this->documents->count();

        $today = Date::today()->toDateString();

        foreach ($this->documents as $item) {
            // Already in transactions
            if ($item->status == 'paid' || $item->status == 'cancelled') {
                continue;
            }

            $transactions = 0;

            foreach ($item->transactions as $transaction) {
                $transactions += $transaction->getAmountConvertedToDefault();
            }

            // Check if it's open or overdue invoice
            if ($item->due_at > $today) {
                $totals['open'] += $item->getAmountConvertedToDefault() - $transactions;
            } else {
                $totals['overdue'] += $item->getAmountConvertedToDefault() - $transactions;
            }
        }

        // Handle payments
        $this->transactions = $this->contact->transactions()->with('account', 'category')->get();

        $this->counts['transactions'] = $this->transactions->count();

        // Prepare data
        $this->transactions->each(function ($item) use (&$totals) {
            $totals['paid'] += $item->getAmountConvertedToDefault();
        });

        $open_amount = money($totals['open']);
        $overdue_amount = money($totals['overdue']);
        $paid_amount = money($totals['paid']);

        $summary_amounts = [
            'open_exact'            => $open_amount->format(),
            'open_for_humans'       => $open_amount->formatForHumans(),
            'overdue_exact'         => $overdue_amount->format(),
            'overdue_for_humans'    => $overdue_amount->formatForHumans(),
            'paid_exact'            => $paid_amount->format(),
            'paid_for_humans'       => $paid_amount->formatForHumans(),
        ];

        $this->summary_amounts = $summary_amounts;

        $this->transactions = $this->paginate($this->transactions->sortByDesc('paid_at'));
        $this->documents = $this->paginate($this->documents->sortByDesc('issued_at'));

        return view('components.contacts.show.content');
    }

    /**
     * Generate a pagination collection.
     *
     * @param array|Collection $items
     * @param int $perPage
     * @param int $page
     * @param array $options
     *
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = null, $page = null, $options = [])
    {
        $perPage = $perPage ?: (int) request('limit', setting('default.list_limit', '25'));

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
