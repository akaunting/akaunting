<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Document\Document;

class Payables extends Widget
{
    public $default_name = 'widgets.payables';

    public $description = 'widgets.description.payables';

    public $report_class = 'Modules\AgedReceivablesPayables\Reports\AgedPayables';

    public function show()
    {
        $this->setData();

        return $this->view('widgets.receivables_payables', $this->data);
    }

    public function setData(): void
    {
        $this->setOpenAndOverdueData(Document::query()->bill(), trans('widgets.total_unpaid_bills'));
    }
}
