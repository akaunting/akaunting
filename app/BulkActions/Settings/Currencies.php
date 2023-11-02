<?php

namespace App\BulkActions\Settings;

use App\Abstracts\BulkAction;
use App\Jobs\Setting\DeleteCurrency;
use App\Jobs\Setting\UpdateCurrency;
use App\Models\Setting\Currency;

class Currencies extends BulkAction
{
    public $model = Currency::class;

    public $text = 'general.currencies';

    public $path = [
        'group' => 'settings',
        'type' => 'currencies',
    ];

    public $actions = [
        'edit' => [
            'icon'          => 'edit',
            'name'          => 'general.edit',
            'message'       => '',
            'permission'    => 'update-settings-currencies',
            'type'          => 'modal',
            'handle'        => 'update',
        ],
        'enable'    => [
            'icon'          => 'check_circle',
            'name'          => 'general.enable',
            'message'       => 'bulk_actions.message.enable',
            'permission'    => 'update-settings-currencies',
        ],
        'disable'   => [
            'icon'          => 'hide_source',
            'name'          => 'general.disable',
            'message'       => 'bulk_actions.message.disable',
            'permission'    => 'update-settings-currencies',
        ],
        'delete'    => [
            'icon'          => 'delete',
            'name'          => 'general.delete',
            'message'       => 'bulk_actions.message.delete',
            'permission'    => 'delete-settings-currencies',
        ],
    ];

    public function edit($request)
    {
        $selected = $this->getSelectedInput($request);

        $precisions = (object) [
            '0' => '0',
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
        ];

        return $this->response('bulk-actions.settings.currencies.edit', compact('selected', 'precisions'));
    }

    public function update($request)
    {
        $currencies = $this->getSelectedRecords($request);

        foreach ($currencies as $currency) {
            try {
                $request->merge([
                    'code' => $currency->code,
                    'enabled' => $currency->enabled,
                ]); // for update job authorize..

                $this->dispatch(new UpdateCurrency($currency, $this->getUpdateRequest($request)));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function disable($request)
    {
        $currencies = $this->getSelectedRecords($request);

        foreach ($currencies as $currency) {
            try {
                $this->dispatch(new UpdateCurrency($currency, $request->merge(['enabled' => 0])));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function destroy($request)
    {
        $currencies = $this->getSelectedRecords($request);

        foreach ($currencies as $currency) {
            try {
                $this->dispatch(new DeleteCurrency($currency));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
}
