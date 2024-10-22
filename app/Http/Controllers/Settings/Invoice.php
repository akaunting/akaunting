<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\SettingController;

class Invoice extends SettingController
{
    public function edit()
    {
        $item_names = [
            'hide' => trans('settings.invoice.hide.item_name'),
            'settings.invoice.item' => trans('settings.invoice.item'),
            'settings.invoice.product' => trans('settings.invoice.product'),
            'settings.invoice.service' =>  trans('settings.invoice.service'),
            'custom' => trans('settings.invoice.custom'),
        ];

        $price_names = [
            'hide' => trans('settings.invoice.hide.price'),
            'settings.invoice.price' => trans('settings.invoice.price'),
            'settings.invoice.rate' => trans('settings.invoice.rate'),
            'custom' => trans('settings.invoice.custom'),
        ];

        $quantity_names = [
            'hide' => trans('settings.invoice.hide.quantity'),
            'settings.invoice.quantity' => trans('settings.invoice.quantity'),
            'custom' => trans('settings.invoice.custom'),
        ];

        return view('settings.invoice.edit', compact(
            'item_names',
            'price_names',
            'quantity_names'
        ));
    }
}
