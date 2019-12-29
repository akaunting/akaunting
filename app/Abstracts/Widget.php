<?php

namespace App\Abstracts;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Income\Invoice;
use App\Traits\Charts;
use Date;

abstract class Widget extends AbstractWidget
{
    use Charts;

    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'width' => 'col-md-4',
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        return $this->show();
    }

    public function calculateDocumentTotals($model)
    {
        $open = $overdue = 0;

        $today = Date::today()->toDateString();

        $type = ($model instanceof Invoice) ? 'invoice' : 'bill';

        $status_field = $type . '_status_code';

        if ($model->$status_field == 'paid') {
            return [$open, $overdue];
        }

        $payments = 0;

        if ($model->$status_field == 'partial') {
            foreach ($model->transactions as $transaction) {
                $payments += $transaction->getAmountConvertedToDefault();
            }
        }

        // Check if the invoice/bill is open or overdue
        if ($model->due_at > $today) {
            $open += $model->getAmountConvertedToDefault() - $payments;
        } else {
            $overdue += $model->getAmountConvertedToDefault() - $payments;
        }

        return [$open, $overdue];
    }
}
