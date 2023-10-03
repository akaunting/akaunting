<?php

namespace App\BulkActions\Settings;

use App\Abstracts\BulkAction;
use App\Jobs\Setting\DeleteTax;
use App\Jobs\Setting\UpdateTax;
use App\Models\Setting\Tax;

class Taxes extends BulkAction
{
    public $model = Tax::class;

    public $text = 'general.taxes';

    public $path = [
        'group' => 'settings',
        'type' => 'taxes',
    ];

    public $actions = [
        'edit' => [
            'icon'          => 'edit',
            'name'          => 'general.edit',
            'message'       => '',
            'permission'    => 'update-settings-taxes',
            'type'          => 'modal',
            'handle'        => 'update',
        ],
        'enable'    => [
            'icon'          => 'check_circle',
            'name'          => 'general.enable',
            'message'       => 'bulk_actions.message.enable',
            'permission'    => 'update-settings-taxes',
        ],
        'disable'   => [
            'icon'          => 'hide_source',
            'name'          => 'general.disable',
            'message'       => 'bulk_actions.message.disable',
            'permission'    => 'update-settings-taxes',
        ],
        'delete'    => [
            'icon'          => 'delete',
            'name'          => 'general.delete',
            'message'       => 'bulk_actions.message.delete',
            'permission'    => 'delete-settings-taxes',
        ],
    ];

    public function edit($request)
    {
        $selected = $this->getSelectedInput($request);

        $types = [
            'fixed' => trans('taxes.fixed'),
            'normal' => trans('taxes.normal'),
            'inclusive' => trans('taxes.inclusive'),
            'withholding' => trans('taxes.withholding'),
            'compound' => trans('taxes.compound'),
        ];

        $disable_options = [];

        if ($compound = Tax::compound()->first()) {
            $disable_options = ['compound'];
        }

        return $this->response('bulk-actions.settings.taxes.edit', compact('selected', 'types', 'disable_options'));
    }

    public function update($request)
    {
        $taxes = $this->getSelectedRecords($request);

        foreach ($taxes as $tax) {
            try {
                $this->dispatch(new UpdateTax($tax, $this->getUpdateRequest($request)));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function disable($request)
    {
        $taxes = $this->getSelectedRecords($request);

        foreach ($taxes as $tax) {
            try {
                $this->dispatch(new UpdateTax($tax, $request->merge(['enabled' => 0])));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function destroy($request)
    {
        $taxes = $this->getSelectedRecords($request);

        foreach ($taxes as $tax) {
            try {
                $this->dispatch(new DeleteTax($tax));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
}
