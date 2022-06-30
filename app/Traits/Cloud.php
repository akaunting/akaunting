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

    public function getCloudRolesPageUrl($location = 'user')
    {
        if (! $this->isCloud()) {
            return 'https://akaunting.com/apps/roles?utm_source=software&utm_medium=' . $location . '&utm_campaign=roles';
        }

        if ($this->moduleIsEnabled('roles')) {
            return route('roles.roles.index');
        }

        return route('cloud.plans.index', [
            'utm_source'    => $location,
            'utm_medium'    => 'app',
            'utm_campaign'  => 'roles',
        ]);
    }

    public function getCloudBankFeedsUrl($location = 'widget')
    {
        if (! $this->isCloud()) {
            return 'https://akaunting.com/apps/bank-feeds?utm_source=software&utm_medium=' . $location . '&utm_campaign=bank_feeds';
        }

        return route('cloud.plans.index', [
            'utm_source'    => $location,
            'utm_medium'    => 'app',
            'utm_campaign'  => 'bank_feeds',
        ]);
    }
}
