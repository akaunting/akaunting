<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Document\Document;
use App\Models\Document\DocumentItem;
use App\Utilities\Recurring;
use App\Utilities\Date;
use App\Traits\Currencies;
use App\Events\Report\TotalCalculating;
use App\Events\Report\TotalCalculated;
use Illuminate\Support\Collection;

class DiscountSummary extends Report
{
    use Currencies;

    public $default_name = 'reports.discount_summary';

    public $icon = 'sell';

    public $type = 'summary';

    public $chart = [
        'income' => [
            'bar' => [
                'colors' => [
                    '#8bb475',
                ],
            ],
            'donut' => [
                //
            ],
        ],
        'expense' => [
            'bar' => [
                'colors' => [
                    '#fb7185',
                ],
            ],
            'donut' => [
                //
            ],
        ],
    ];

    public function setTables()
    {
        $this->tables = [
            'income' => trans_choice('general.incomes', 1),
            'expense' => trans_choice('general.expenses', 2),
        ];
    }

    public function setData()
    {
        $invoices = $this->applyFilters(
            model: Document::invoice()->with('totals', 'items')->accrued(),
            args: ['date_field' => 'issued_at', 'model_type' => 'invoice'],
        )->get();
        Recurring::reflect($invoices, 'issued_at');
        $this->setTotals($invoices, 'issued_at', false, 'income');

        // Bills
        $bills = $this->applyFilters(
            model: Document::bill()->with('totals', 'items')->accrued(),
            args: ['date_field' => 'issued_at', 'model_type' => 'bill'],
        )->get();
        Recurring::reflect($bills, 'issued_at');
        $this->setTotals($bills, 'issued_at', false, 'expense');
    }

    public function setTotals($items, $date_field, $check_type = false, $table = 'default', $with_tax = true)
    {
        event(new TotalCalculating($this, $items, $date_field, $check_type, $table, $with_tax));

        $group_field = $this->getGroup() . '_id';

        $codes = $this->getDiscountCodes();

        foreach ($items as $item) {
            $date = $this->getFormattedDate(Date::parse($item->$date_field));

            if (! isset($this->footer_totals[$table][$date])) {
                continue;
            }

            $type = ($item->type === Document::INVOICE_TYPE || $item->type === 'income') ? 'income' : 'expense';

            // One line per document item, grouped the way the other summaries group theirs
            foreach ($this->getDiscountLines($item, $codes) as $line) {
                // Make groups extensible
                $line = $this->applyGroups($line);

                if (! isset($line->$group_field)) {
                    continue;
                }

                $group = $line->$group_field;

                if (! isset($this->row_values[$table][$group][$date])) {
                    continue;
                }

                $amount = $this->convertToDefault($line->amount, $item->currency_code, $item->currency_rate);

                if (($check_type == false) || ($type == 'income')) {
                    $this->row_values[$table][$group][$date] += $amount;

                    $this->footer_totals[$table][$date] += $amount;
                } else {
                    $this->row_values[$table][$group][$date] -= $amount;

                    $this->footer_totals[$table][$date] -= $amount;
                }
            }
        }

        event(new TotalCalculated($this, $items, $date_field, $check_type, $table, $with_tax));
    }

    /**
     * The discounts of the document, one line per item carrying the item category.
     *
     * The line discount is worked out from the item itself: the item discount total
     * of the document takes a percentage of the already discounted amount, i.e. 9
     * for 10% of 100. The total discount is shared out the way the document does
     * it, by what each item comes to after its own discount.
     */
    protected function getDiscountLines(Document $document, array $codes): Collection
    {
        $amounts = [];

        foreach ($document->totals as $total) {
            if (! in_array($total->code, $codes) || empty($total->amount)) {
                continue;
            }

            $line_amounts = ($total->code === 'item_discount')
                ? $this->getItemDiscounts($document)
                : [];

            // Nothing on the items to go by, share the stored amount out like a total discount
            if (empty(array_filter($line_amounts))) {
                $line_amounts = $this->shareOut($document, (float) $total->amount);
            }

            foreach ($line_amounts as $key => $amount) {
                $amounts[$key] = ($amounts[$key] ?? 0) + $amount;
            }
        }

        return $document->items
            ->filter(fn (DocumentItem $item, $key) => ! empty($amounts[$key]))
            ->map(function (DocumentItem $item, $key) use ($document, $amounts) {
                $line = clone $item;
                $line->type = $document->type;
                $line->document_number = $document->document_number;
                $line->status = $document->status;
                $line->issued_at = $document->issued_at;
                $line->due_at = $document->due_at;
                $line->currency_code = $document->currency_code;
                $line->currency_rate = $document->currency_rate;
                $line->category_id = $item->category_id ?: $document->category_id;
                $line->contact_id = $document->contact_id;
                $line->amount = $amounts[$key];

                return $line;
            })
            ->values();
    }

    /**
     * The line discount of each item, keyed like the items of the document.
     *
     * The stored rate is read, the model accessor reports it as zero once the
     * discount location setting is back on total.
     */
    protected function getItemDiscounts(Document $document): array
    {
        return $document->items->map(function (DocumentItem $item) {
            $rate = (float) $item->getRawOriginal('discount_rate');

            if (empty($rate)) {
                return 0;
            }

            if ($item->discount_type === 'percentage') {
                return (float) $item->price * (float) $item->quantity * ($rate / 100);
            }

            return $rate;
        })->all();
    }

    /**
     * Share the amount out to the items of the document by their totals.
     */
    protected function shareOut(Document $document, float $amount): array
    {
        $totals = $document->items->map(fn (DocumentItem $item) => (float) $item->total);

        $sum = $totals->sum();

        if (empty($sum)) {
            return [];
        }

        return $totals->map(fn ($total) => $amount * $total / $sum)->all();
    }

    /**
     * The document total codes the discount filter asks for.
     */
    protected function getDiscountCodes(): array
    {
        return match ($this->getDiscount()) {
            'item' => ['item_discount'],
            'total' => ['discount'],
            default => ['item_discount', 'discount'],
        };
    }

    public function getFields()
    {
        return [
            $this->getGroupField(),
            $this->getPeriodField(),
        ];
    }
}
