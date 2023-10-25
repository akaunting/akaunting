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

        return route('apps.app.show', [
            'alias'         => 'roles',
            'utm_source'    => $location,
            'utm_medium'    => 'app',
            'utm_campaign'  => 'roles',
        ]);
    }

    public function getCloudBankFeedsUrl($location = 'widget')
    {
        if ($this->moduleIsEnabled('bank-feeds')) {
            return route('bank-feeds.bank-connections.index');
        }

        return route('apps.app.show', [
            'alias'         => 'bank-feeds',
            'utm_source'    => $location,
            'utm_medium'    => 'app',
            'utm_campaign'  => 'bank_feeds',
        ]);
    }

    public function isCloud()
    {
        return request()->getHost() == config('cloud.host', 'app.akaunting.com');
    }
}
