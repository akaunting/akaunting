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
        $user_limit = $this->getUserLimitOfPlan();
        $company_limit = $this->getCompanyLimitOfPlan();
        $invoice_limit = $this->getInvoiceLimitOfPlan();

        if (! $user_limit->action_status) {
            return $user_limit;
        }

        if (! $company_limit->action_status) {
            return $company_limit;
        }

        if (! $invoice_limit->action_status) {
            return $invoice_limit;
        }

        $limit = new \stdClass();
        $limit->action_status = true;
        $limit->view_status = true;
        $limit->message = "Success";

        return $limit;
    }

    public function getPlanLimitByType($type): object
    {
        if (! config('app.installed') || running_in_test()) {
            $limit = new \stdClass();

            $limit->action_status = true;
            $limit->view_status = true;
            $limit->message = "Success";

            return $limit;
        }

        if (! $data = $this->getPlanLimits()) {
            $limit = new \stdClass();

            $limit->action_status = false;
            $limit->view_status = false;
            $limit->message = "Not able to create a new $type.";

            return $limit;
        }

        $limit = $data->$type;

        $limit->message = str_replace('{company_id}', company_id(), $limit->message);

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
