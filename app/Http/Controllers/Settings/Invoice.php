<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Models\Setting\Setting;

class Invoice extends Controller
{
    public function edit()
    {
        $setting = Setting::prefix('invoice')->get()->transform(function ($s) {
            $s->key = str_replace('invoice.', '', $s->key);

            return $s;
        })->pluck('value', 'key');

        $item_names = [
            'settings.invoice.item' => trans('settings.invoice.item'),
            'settings.invoice.product' => trans('settings.invoice.product'),
            'settings.invoice.service' =>  trans('settings.invoice.service'),
            'custom' => trans('settings.invoice.custom'),
        ];

        $price_names = [
            'settings.invoice.price' => trans('settings.invoice.price'),
            'settings.invoice.rate' => trans('settings.invoice.rate'),
            'custom' => trans('settings.invoice.custom'),
        ];

        $quantity_names = [
            'settings.invoice.quantity' => trans('settings.invoice.quantity'),
            'custom' => trans('settings.invoice.custom'),
        ];

        $payment_terms = [
            '0' => trans('settings.invoice.due_receipt'),
            '15' => trans('settings.invoice.due_days', ['days' => 15]),
            '30' => trans('settings.invoice.due_days', ['days' => 30]),
            '45' => trans('settings.invoice.due_days', ['days' => 45]),
            '60' => trans('settings.invoice.due_days', ['days' => 60]),
            '90' => trans('settings.invoice.due_days', ['days' => 90]),
        ];

        return view('settings.invoice.edit', compact(
            'setting',
            'item_names',
            'price_names',
            'quantity_names',
            'payment_terms'
        ));
    }
}
