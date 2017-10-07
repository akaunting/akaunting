<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Http\Requests\Item\Item as Request;
use App\Models\Item\Item;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Traits\Uploads;

class Items extends Controller
{
    use Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $items = Item::with('category')->collect();

        $categories = Category::enabled()->type('item')->pluck('name', 'id')
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.categories', 2)]), '');

        return view('items.items.index', compact('items', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $categories = Category::enabled()->type('item')->pluck('name', 'id');

        $taxes = Tax::enabled()->pluck('name', 'id');

        return view('items.items.create', compact('categories', 'taxes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // Upload picture
        $picture_path = $this->getUploadedFilePath($request->file('picture'), 'items');
        if ($picture_path) {
            $request['picture'] = $picture_path;
        }

        Item::create($request->input());

        $message = trans('messages.success.added', ['type' => trans_choice('general.items', 1)]);

        flash($message)->success();

        return redirect('items/items');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Item  $item
     *
     * @return Response
     */
    public function edit(Item $item)
    {
        $categories = Category::enabled()->type('item')->pluck('name', 'id');

        $taxes = Tax::enabled()->pluck('name', 'id');

        return view('items.items.edit', compact('item', 'categories', 'taxes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Item  $item
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Item $item, Request $request)
    {
        // Upload picture
        $picture_path = $this->getUploadedFilePath($request->file('picture'), 'items');
        if ($picture_path) {
            $request['picture'] = $picture_path;
        }

        $item->update($request->input());

        $message = trans('messages.success.updated', ['type' => trans_choice('general.items', 1)]);

        flash($message)->success();

        return redirect('items/items');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Item  $item
     *
     * @return Response
     */
    public function destroy(Item $item)
    {
        $canDelete = $item->canDelete();

        if ($canDelete === true) {
            $item->delete();

            $message = trans('messages.success.deleted', ['type' => trans_choice('general.items', 1)]);

            flash($message)->success();
        } else {
            $text = array();

            if (isset($canDelete['bills'])) {
                $text[] = '<b>' . $canDelete['bills'] . '</b> ' . trans_choice('general.bills', ($canDelete['bills'] > 1) ? 2 : 1);
            }

            if (isset($canDelete['invoices'])) {
                $text[] = '<b>' . $canDelete['invoices'] . '</b> ' . trans_choice('general.items', ($canDelete['invoices'] > 1) ? 2 : 1);
            }

            $message = trans('messages.warning.deleted', ['type' => trans_choice('general.items', 1), 'text' => implode(', ', $text)]);

            flash($message)->warning();
        }

        return redirect('items/items');
    }

    public function autocomplete()
    {
        $type = request('type');
        $query = request('query');
        $currency_code = request('currency_code');

        if (empty($currency_code) || (strtolower($currency_code)  == 'null')) {
            $currency_code = setting('general.default_currency');
        }

        $currency = Currency::where('code', $currency_code)->first();

        $filter_data = array(
            'name' => $query
        );

        $items = Item::getItems($filter_data);

        if ($items) {
            foreach ($items as $item) {
                $tax = Tax::find($item->tax_id);

                $item_tax_price = 0;

                if (!empty($tax)) {
                    $item_tax_price = ($item->sale_price / 100) * $tax->rate;
                }

                $item->sale_price = $this->convertPrice($item->sale_price, $currency_code, $currency->rate);
                $item->purchase_price = $this->convertPrice($item->purchase_price, $currency_code, $currency->rate);

                switch ($type) {
                    case 'bill':
                        $total = $item->purchase_price + $item_tax_price;
                        break;
                    case 'invoice':
                    default:
                        $total = $item->sale_price + $item_tax_price;
                        break;
                }

                $item->total = money($total, $currency_code, true)->format();
            }
        }

        return response()->json($items);
    }

    public function totalItem()
    {
        $input_items = request('item');
        $currency_code = request('currency_code');

        if (empty($currency_code)) {
            $currency_code = setting('general.default_currency');
        }

        $json = new \stdClass;

        $sub_total = 0;
        $tax_total = 0;

        $items = array();

        if ($input_items) {
            foreach ($input_items as $key => $item) {
                $item_tax_total= 0;
                $item_sub_total = ($item['price'] * $item['quantity']);

                if (!empty($item['tax_id'])) {
                    $tax = Tax::find($item['tax_id']);

                    $item_tax_total = (($item['price'] * $item['quantity']) / 100) * $tax->rate;
                }

                $sub_total += $item_sub_total;
                $tax_total += $item_tax_total;

                $total = $item_sub_total + $item_tax_total;

                $items[$key] = money($total, $currency_code, true)->format();
            }
        }

        $json->items = $items;

        $json->sub_total = money($sub_total, $currency_code, true)->format();

        $json->tax_total = money($tax_total, $currency_code, true)->format();

        $grand_total = $sub_total + $tax_total;

        $json->grand_total = money($grand_total, $currency_code, true)->format();

        return response()->json($json);
    }

    protected function convertPrice($amount, $currency_code, $currency_rate, $format = false, $reverse = false)
    {
        $item = new Item();

        $item->amount = $amount;
        $item->currency_code = $currency_code;
        $item->currency_rate = $currency_rate;

        if ($reverse) {
            return $item->getReverseConvertedAmount($format);
        }

        return $item->getConvertedAmount($format);
    }
}
