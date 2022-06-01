<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Traits\Cloud;
use App\Traits\Modules;

class BankFeeds extends Widget
{
    use Cloud, Modules;

    public $default_name = 'widgets.bank_feeds';

    public function show()
    {
        $module = $this->getModulesByWidget('bank-feeds');

        return $this->view('widgets.bank_feeds', [
            'module'            => $module,
            'learn_more_url'    => $this->getCloudBankFeedsUrl(),
        ]);
    }
}
