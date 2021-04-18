<?php

namespace App\BulkActions\Common;

use App\Abstracts\BulkAction;
use App\Jobs\Common\DeleteCompany;
use App\Jobs\Common\UpdateCompany;
use App\Models\Common\Company;

class Companies extends BulkAction
{
    public $model = Company::class;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-common-companies',
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-common-companies',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-common-companies',
        ],
    ];

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
