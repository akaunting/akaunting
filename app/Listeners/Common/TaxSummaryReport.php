<?php

namespace App\Listeners\Common;

use App\Abstracts\Listeners\Report as Listener;

class TaxSummaryReport extends Listener
{
    protected $class = 'App\Reports\TaxSummary';
}