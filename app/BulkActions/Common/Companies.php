<?php

namespace App\BulkActions\Common;

use Akaunting\Money\Currency as MoneyCurrency;
use App\Abstracts\BulkAction;
use App\Jobs\Common\DeleteCompany;
use App\Jobs\Common\UpdateCompany;
use App\Models\Common\Company;
use App\Traits\Users;

class Companies extends BulkAction
{
    use Users;

    public $model = Company::class;

    public $text = 'general.companies';

    public $path = [
        'group' => 'common',
        'type' => 'companies',
    ];

    public $actions = [
        'edit' => [
            'icon'          => 'edit',
            'name'          => 'general.edit',
            'message'       => '',
            'permission'    => 'update-common-companies',
            'type'          => 'modal',
            'handle'        => 'update',
        ],
        'enable'    => [
            'icon'          => 'check_circle',
            'name'          => 'general.enable',
            'message'       => 'bulk_actions.message.enable',
            'permission'    => 'update-common-companies',
        ],
        'disable'   => [
            'icon'          => 'hide_source',
            'name'          => 'general.disable',
            'message'       => 'bulk_actions.message.disable',
            'permission'    => 'update-common-companies',
        ],
    ];

    public function edit($request)
    {
        $selected = $this->getSelectedInput($request);

        $money_currencies = MoneyCurrency::getCurrencies();

        $currencies = [];

        foreach ($money_currencies as $key => $item) {
            $currencies[$key] = $key . ' - ' . $item['name'];
        }

        return $this->response('bulk-actions.common.companies.edit', compact('selected', 'currencies'));
    }

    public function update($request)
    {
        $companies = $this->getSelectedRecords($request);

        foreach ($companies as $company) {
            try {
                if ($this->isNotUserCompany($company->id)) {
                    continue;
                }

                $request->merge([
                    'enabled' => $company->enabled,
                ]); // for update job authorize..

                $this->dispatch(new UpdateCompany($company, $this->getUpdateRequest($request), company_id()));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function enable($request)
    {
        $companies = $this->getSelectedRecords($request);

        foreach ($companies as $company) {
            try {
                $this->dispatch(new UpdateCompany($company, $request->merge(['enabled' => 1])));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function disable($request)
    {
        $companies = $this->getSelectedRecords($request);

        foreach ($companies as $company) {
            try {
                $this->dispatch(new UpdateCompany($company, $request->merge(['enabled' => 0])));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function destroy($request)
    {
        $companies = $this->getSelectedRecords($request);

        foreach ($companies as $company) {
            try {
                $this->dispatch(new DeleteCompany($company));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
}
