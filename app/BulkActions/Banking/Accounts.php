<?php

namespace App\BulkActions\Banking;

use App\Abstracts\BulkAction;
use App\Jobs\Banking\DeleteAccount;
use App\Jobs\Banking\UpdateAccount;
use App\Models\Banking\Account;

class Accounts extends BulkAction
{
    public $model = Account::class;

    public $text = 'general.accounts';

    public $path = [
        'group' => 'banking',
        'type' => 'accounts',
    ];

    public $actions = [
        'enable'    => [
            'icon'          => 'check_circle',
            'name'          => 'general.enable',
            'message'       => 'bulk_actions.message.enable',
            'permission'    => 'update-banking-accounts',
        ],
        'disable'   => [
            'icon'          => 'hide_source',
            'name'          => 'general.disable',
            'message'       => 'bulk_actions.message.disable',
            'permission'    => 'update-banking-accounts',
        ],
        'delete'    => [
            'icon'          => 'delete',
            'name'          => 'general.delete',
            'message'       => 'bulk_actions.message.delete',
            'permission'    => 'delete-banking-accounts',
        ],
    ];

    public function disable($request)
    {
        $accounts = $this->getSelectedRecords($request);

        foreach ($accounts as $account) {
            try {
                $this->dispatch(new UpdateAccount($account, $request->merge(['enabled' => 0])));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function destroy($request)
    {
        $accounts = $this->getSelectedRecords($request);

        foreach ($accounts as $account) {
            try {
                $this->dispatch(new DeleteAccount($account));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
}
