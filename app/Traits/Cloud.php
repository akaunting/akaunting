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

    /**
     * Whether the company has Bank Feeds, so that accounts are offered a bank connection
     * rather than the app itself.
     */
    public function hasCloudBankFeeds(): bool
    {
        return $this->moduleIsEnabled('bank-feeds');
    }

    /**
     * Whether the user can connect a bank from here, so that a new account starts at the bank
     * connection rather than the form for an account kept by hand.
     */
    public function canConnectCloudBankFeeds(): bool
    {
        return $this->hasCloudBankFeeds() && (bool) user()?->can('create-bank-feeds-bank-connections');
    }

    public function getCloudBankFeedsUrl($location = 'widget')
    {
        if ($this->hasCloudBankFeeds()) {
            return $this->canConnectCloudBankFeeds()
                ? route('bank-feeds.bank-connections.create')
                : route('accounts.index');
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
