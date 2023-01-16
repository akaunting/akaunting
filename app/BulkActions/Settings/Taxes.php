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
