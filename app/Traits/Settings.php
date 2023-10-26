<?php

namespace App\Traits;

use App\Models\Setting\Setting;
use App\Traits\Companies;

trait Settings
{
    use Companies;

    public function getSettingValue(string $key, mixed $default = null): mixed
    {
        $settings = setting()->all();

        if (! empty($settings)) {
            return setting($key, $default);
        }

        $company_id = $this->getCompanyId();

        if (empty($company_id)) {
            return $default;
        }

        $value = Setting::companyId($company_id)->where('key', $key)->pluck('value')->first();

        return $value ?: $default;
    }
}
