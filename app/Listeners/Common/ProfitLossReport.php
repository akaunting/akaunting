<?php

namespace App\Listeners\Common;

use App\Abstracts\Listeners\Report as Listener;

class ProfitLossReport extends Listener
{
    protected $class = 'App\Reports\ProfitLoss';
}