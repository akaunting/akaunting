<?php

namespace App\Traits;

use App\Models\Setting\Setting;
use App\Traits\Companies;

trait Settings
{
    use Companies;

    public function getSettingValue(string $key, mixed $default = ''): mixed
    {
        $settings = setting()->all();

        if (! empty($settings)) {
            return setting($key);
        }

        $company_id = $this->getCompanyId();

        if (empty($company_id)) {
            return $default;
        }

        return Setting::companyId($company_id)->where('key', $key)->pluck('value')->first();
    }
}
