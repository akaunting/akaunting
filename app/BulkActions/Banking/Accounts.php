<?php

namespace App\BulkActions\Banking;

use App\Abstracts\BulkAction;
use App\Models\Banking\Account;

class Accounts extends BulkAction
{

    public $model = Account::class;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-banking-accounts'
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-banking-accounts'
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.deletes',
            'permission' => 'delete-banking-accounts'
        ]
    ];

    public function disable($request)
    {
        $selected = $request->get('selected', []);

        $accounts = $this->model::find($selected);

        foreach ($accounts as $account) {
            if ($account->id == setting('default.account')) {
                $relationships[] = strtolower(trans_choice('general.companies', 1));
            }

            if (empty($relationships)) {
                $account->enabled = 0;
                $account->save();

                $message = trans('messages.success.disabled', ['type' => $account->name]);

                return $this->itemResponse($account->fresh(), new Transformer(), $message);
            } else {
                $message = trans('messages.warning.disabled', ['name' => $account->name, 'text' => implode(', ', $relationships)]);

                $this->response->errorUnauthorized($message);
            }
        }
    }

    public function delete($request)
    {
        $this->destroy($request);
    }

    public function destroy($request)
    {
        $selected = $request->get('selected', []);

        $accounts = $this->model::find($selected);

        foreach ($accounts as $account) {
            if ($relationships = $this->getRelationships($account)) {
                if ($account->id == setting('default.account')) {
                    $relationships[] = strtolower(trans_choice('general.companies', 1));
                }
            }

            if (empty($relationships)) {
                $account->delete();

                $message = trans('messages.success.deleted', ['type' => $account->name]);

                return new Response($message);
            } else {
                $message = trans('messages.warning.deleted', ['name' => $account->name, 'text' => implode(', ', $relationships)]);

                $this->response->errorUnauthorized($message);
            }
        }
    }

    protected function getRelationships($account)
    {
        $relationships = $this->countRelationships($account, [
            'payments' => 'payments',
            'revenues' => 'revenues',
        ]);

        return $relationships;
    }
}
