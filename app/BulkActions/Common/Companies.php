<?php

namespace App\BulkActions\Common;

use App\Abstracts\BulkAction;
use App\Models\Common\Company;

class Companies extends BulkAction
{

    public $model = Company::class;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-common-companies'
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-common-companies'
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.deletes',
            'permission' => 'delete-common-companies'
        ]
    ];

    public function enable($request)
    {
        try {
            $selected = $request->get('selected', []);

            $companies = $this->model::find($selected);

            foreach ($companies as $company) {
                // Check if user can access company
                $this->owner($company);

                $company->enabled = 1;
                $company->save();
            }

            $message = trans('messages.success.enabled', ['type' => $company->name]);

            return $this->itemResponse($company->fresh(), new Transformer(), $message);
        } catch (\HttpException $e) {
            $this->response->errorUnauthorized(trans('companies.error.not_user_company'));
        }
    }

    public function disable($request)
    {
        try {
            $selected = $request->get('selected', []);

            $companies = $this->model::find($selected);

            foreach ($companies as $company) {
                // Check if user can access company
                $this->owner($company);

                $company->enabled = 0;
                $company->save();
            }
        } catch (\HttpException $e) {
            $this->response->errorUnauthorized(trans('companies.error.not_user_company'));
        }
    }

    public function delete($request)
    {
        $this->destroy($request);
    }

    public function destroy($request)
    {
        $selected = $request->get('selected', []);

        $companies = $this->model::find($selected);

        foreach ($companies as $company) {
            // Can't delete active company
            if ($company->id == session('company_id')) {
                $content = trans('companies.error.delete_active');

                return new Response($content, 422);
            }

            try {
                // Check if user can access company
                $this->owner($company);

                $company->delete();

                $message = trans('messages.success.deleted', ['type' => $company->name]);

                return new Response($message);
            } catch (\HttpException $e) {
                $this->response->errorUnauthorized(trans('companies.error.not_user_company'));
            }
        }
    }

    public function owner(Company $company)
    {
        $companies = user()->companies()->pluck('id')->toArray();

        if (in_array($company->id, $companies)) {
            return new Response('');
        }

        $this->response->errorUnauthorized(trans('companies.error.not_user_company'));
    }
}
