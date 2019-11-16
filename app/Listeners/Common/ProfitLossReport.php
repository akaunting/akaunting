<?php

namespace App\Listeners\Common;

use App\Abstracts\Reports\Listener;

class ProfitLossReport extends Listener
{
    protected $class = 'App\Reports\ProfitLoss';
}