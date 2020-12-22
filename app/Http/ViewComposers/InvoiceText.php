<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class InvoiceText
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $text_override = [];

        $text_items = setting('invoice.item_name', 'general.items');

        if ($text_items == 'custom') {
            $text_items = setting('invoice.item_input');
        }

        $text_quantity = setting('invoice.quantity_name', 'invoices.quantity');

        if ($text_quantity == 'custom') {
            $text_quantity = setting('invoice.quantity_input');
        }

        $text_price = setting('invoice.price_name', 'invoices.price');

        if ($text_price == 'custom') {
            $text_price = setting('invoice.price_input');
        }

        $text_override['items'] = $text_items;
        $text_override['quantity'] = $text_quantity;
        $text_override['price'] = $text_price;

        $view->with(['text_override' => $text_override]);
    }

}