<?php

namespace App\Traits;

use App\Traits\SiteApi;
use App\Utilities\Date;
use Illuminate\Support\Facades\Cache;

trait Plans
{
    use SiteApi;

    public function clearPlansCache(): void
    {
        Cache::forget('plans.limits');
    }

    public function getUserLimitOfPlan(): object
    {
        return $this->getPlanLimitByType('user');
    }

    public function getCompanyLimitOfPlan(): object
    {
        return $this->getPlanLimitByType('company');
    }

    public function getInvoiceLimitOfPlan(): object
    {
        return $this->getPlanLimitByType('invoice');
    }

    public function getAnyActionLimitOfPlan(): object
    {
        $limit = new \stdClass();
        $limit->action_status = true;
        $limit->view_status = true;
        $limit->message = "Success";

        return $limit;
    }

    public function getPlanLimitByType($type): object
    {
        $limit = new \stdClass();

        $limit->action_status = true;
        $limit->view_status = true;
        $limit->message = "Success";

        return $limit;
    }

    public function getPlanLimits(): bool|object
    {
        $key = 'plans.limits';

        return Cache::remember($key, Date::now()->addHour(), function () {
            $url = 'plans/limits';

            if (! $data = static::getResponseData('GET', $url, ['timeout' => 10])) {
                return false;
            }

            return $data;
        });
    }
}
