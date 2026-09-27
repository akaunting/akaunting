<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Document\Document;

class Receivables extends Widget
{
    public $default_name = 'widgets.receivables';

    public $description = 'widgets.description.receivables';

    public $report_class = 'Modules\AgedReceivablesPayables\Reports\AgedReceivables';

    public function show()
    {
        $this->setData();

        return $this->view('widgets.receivables_payables', $this->data);
    }

    public function setData(): void
    {
        $this->setOpenAndOverdueData(Document::query()->invoice(), trans('widgets.total_unpaid_invoices'));
    }
}
