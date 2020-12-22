<?php

namespace App\Listeners\Update\V20;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\DB;

class Version205 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.0.5';

    protected $items;

    protected $categories;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->items = [];

        $this->updateBillItems();

        $this->updateInvoiceItems();
    }

    protected function updateBillItems()
    {
        $bill_items = DB::table('bill_items')->whereNull('deleted_at')->where('item_id', 0)->cursor();

        foreach ($bill_items as $bill_item) {
            $item_id = $this->getItemId($bill_item);

            DB::table('bill_items')
                ->where('id', $bill_item->id)
                ->update(['item_id' => $item_id]);
        }
    }

    protected function updateInvoiceItems()
    {
        $invoice_items = DB::table('invoice_items')->whereNull('deleted_at')->where('item_id', 0)->cursor();

        foreach ($invoice_items as $invoice_item) {
            $item_id = $this->getItemId($invoice_item);

            DB::table('invoice_items')
                ->where('id', $invoice_item->id)
                ->update(['item_id' => $item_id]);

            DB::table('items')
                ->where('id', $item_id)
                ->update(['sale_price' => $invoice_item->price]);
        }
    }

    protected function getItemId($item)
    {
        // Set category_id for company.
        if (!isset($this->categories[$item->company_id])) {
            $this->categories[$item->company_id] = DB::table('categories')->where('company_id', $item->company_id)->where('type', 'item')->first()->id;
        }

        // Return set item_id for item name.
        if (isset($this->items[$item->company_id]) && in_array($item->name, $this->items[$item->company_id])) {
            return array_search($item->name, $this->items[$item->company_id]);
        }

        // Insert new item.
        $item_id = DB::table('items')->insertGetId([
            'company_id' => $item->company_id,
            'name' => $item->name,
            'description' => null,
            'sale_price' => $item->price,
            'purchase_price' => $item->price,
            'category_id' => $this->categories[$item->company_id],
            'tax_id' => null,
            'enabled' => 1,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'deleted_at' => null,
        ]);

        // Set item_id for item name.
        $this->items[$item->company_id][$item_id] = $item->name;

        return $item_id;
    }
}
