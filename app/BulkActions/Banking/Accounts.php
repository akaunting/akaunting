<?php

namespace App\BulkActions\Banking;

use App\Abstracts\BulkAction;
use App\Jobs\Banking\DeleteAccount;
use App\Jobs\Banking\UpdateAccount;
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
            try {
                $this->dispatch(new UpdateAccount($account, request()->merge(['enabled' => 1])));
            } catch (\Exception $e) {
                return $e->getMessage();
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
            try {
                $this->dispatch(new DeleteAccount($account));
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }
}
