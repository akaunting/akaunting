<?php

namespace App\Traits;

use App\Traits\Modules;

trait Cloud
{
    use Modules;

    public $cloud_host = 'app.akaunting.com';

    public function isCloud()
    {
        return request()->getHost() == $this->cloud_host;
    }

    public function getCloudRolesPageUrl()
    {
        if (! $this->isCloud()) {
            return 'https://akaunting.com/plans?utm_source=user_role&utm_medium=software&utm_campaign=plg';
        }

        if ($this->moduleIsEnabled('roles')) {
            return route('roles.roles.index');
        }

        return route('cloud.plans.index', [
            'utm_source'    => 'user',
            'utm_medium'    => 'app',
            'utm_campaign'  => 'roles',
        ]);
    }

    public function getCloudBankFeedsUrl()
    {
        if (! $this->isCloud()) {
            return 'https://akaunting.com/features/connect-your-bank?utm_source=bank_feeds_widget&utm_medium=software&utm_campaign=plg';
        }

        return route('cloud.plans.index', [
            'utm_source'    => 'widget',
            'utm_medium'    => 'app',
            'utm_campaign'  => 'bank_feeds',
        ]);
    }
}
