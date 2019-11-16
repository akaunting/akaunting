<?php

namespace App\Listeners\Common;

use App\Abstracts\Reports\Listener;

class TaxSummaryReport extends Listener
{
    protected $class = 'App\Reports\TaxSummary';
}