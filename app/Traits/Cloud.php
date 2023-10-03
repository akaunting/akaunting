<?php

namespace App\Traits;

use App\Traits\Modules;

trait Cloud
{
    use Modules;

    public function getCloudRolesPageUrl($location = 'user')
    {
        if ($this->moduleIsEnabled('roles')) {
            return route('roles.roles.index');
        }

        if (request()->isNotCloudHost()) {
            return 'https://akaunting.com/apps/roles?utm_source=software&utm_medium=' . $location . '&utm_campaign=roles';
        }

        return route('cloud.plans.index', [
            'utm_source'    => $location,
            'utm_medium'    => 'app',
            'utm_campaign'  => 'roles',
        ]);
    }

    public function getCloudBankFeedsUrl($location = 'widget')
    {
        if (request()->isNotCloudHost()) {
            return 'https://akaunting.com/apps/bank-feeds?utm_source=software&utm_medium=' . $location . '&utm_campaign=bank_feeds';
        }

        return route('cloud.plans.index', [
            'utm_source'    => $location,
            'utm_medium'    => 'app',
            'utm_campaign'  => 'bank_feeds',
        ]);
    }

    // @deprecated 3.1
    public function isCloud()
    {
        return request()->isCloudHost();
    }
}
