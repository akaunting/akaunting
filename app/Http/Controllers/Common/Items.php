<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Exports\Common\Items as Export;
use App\Http\Requests\Common\Item as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Http\Requests\Common\TotalItem as TotalRequest;
use App\Imports\Common\Items as Import;
use App\Jobs\Common\CreateItem;
use App\Jobs\Common\DeleteItem;
use App\Jobs\Common\UpdateItem;
use App\Models\Common\Item;
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
        $items = Item::with(['category', 'tax'])->collect();

        return view('common.items.index', compact('items'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('items.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $categories = Category::type('item')->enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $currency = Currency::where('code', setting('default.currency', 'USD'))->first();

        return view('common.items.create', compact('categories', 'taxes', 'currency'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $response = $this->ajaxDispatch(new CreateItem($request));

        if ($response['success']) {
            $response['redirect'] = route('items.index');

            $message = trans('messages.success.added', ['type' => trans_choice('general.items', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('items.create');

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Item  $item
     *
     * @return Response
     */
    public function duplicate(Item $item)
    {
        $clone = $item->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.items', 1)]);

        flash($message)->success();

        return redirect()->route('items.edit', $clone->id);
    }

    /**
     * Import the specified resource.
     *
     * @param  ImportRequest  $request
     *
     * @return Response
     */
    public function import(ImportRequest $request)
    {
        \Excel::import(new Import(), $request->file('import'));

        $message = trans('messages.success.imported', ['type' => trans_choice('general.items', 2)]);

        flash($message)->success();

        return redirect()->route('items.index');
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
        $categories = Category::type('item')->enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        return view('common.items.edit', compact('item', 'categories', 'taxes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $item
     * @param  $request
     * @return Response
     */
    public function update(Item $item, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateItem($item, $request));

        if ($response['success']) {
            $response['redirect'] = route('items.index');

            $message = trans('messages.success.updated', ['type' => $item->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('items.edit', $item->id);

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Item $item
     *
     * @return Response
     */
    public function enable(Item $item)
    {
        $response = $this->ajaxDispatch(new UpdateItem($item, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $item->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Item $item
     *
     * @return Response
     */
    public function disable(Item $item)
    {
        $response = $this->ajaxDispatch(new UpdateItem($item, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $item->name]);
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Item $item
     *
     * @return Response
     */
    public function destroy(Item $item)
    {
        $response = $this->ajaxDispatch(new DeleteItem($item));

        $response['redirect'] = route('items.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $item->name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Export the specified resource.
     *
     * @return Response
     */
    public function export()
    {
        return \Excel::download(new Export(), \Str::filename(trans_choice('general.items', 2)) . '.xlsx');
    }

    public function autocomplete()
    {
        $type = request('type');
        $query = request('query');
        $currency_code = request('currency_code');

        if (empty($currency_code) || (strtolower($currency_code)  == 'null')) {
            $currency_code = setting('default.currency');
        }

        $autocomplete = Item::autocomplete([
            'name' => $query
        ]);

        $items = $autocomplete->get();

        if ($items) {
            foreach ($items as $item) {
                $tax = Tax::find($item->tax_id);

                $item_tax_price = 0;

                if (!empty($tax)) {
                    $item_tax_price = ($item->sale_price / 100) * $tax->rate;
                }

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

        return response()->json([
            'success' => true,
            'message' => 'Get all items.',
            'errors' => [],
            'count' => $items->count(),
            'data' => ($items->count()) ? $items : null,
        ]);
    }

    public function total(TotalRequest $request)
    {
        $input_items = $request->input('items');
        $currency_code = $request->input('currency_code');
        $discount = $request->input('discount');

        if (empty($currency_code)) {
            $currency_code = setting('default.currency');
        }

        $json = new \stdClass();

        $sub_total = 0;
        $tax_total = 0;

        $items = [];

        if ($input_items) {
            foreach ($input_items as $key => $item) {
                $price = (double) $item['price'];
                $quantity = (double) $item['quantity'];

                $item_tax_total = 0;
                $item_tax_amount = 0;

                $item_sub_total = ($price * $quantity);
                $item_discounted_total = $item_sub_total;

                // Apply discount to amount
                if ($discount) {
                    $item_discounted_total = $item_sub_total - ($item_sub_total * ($discount / 100));
                }

                if (!empty($item['tax_id'])) {
                    $inclusives = $compounds = [];

                    foreach ($item['tax_id'] as $tax_id) {
                        $tax = Tax::find($tax_id);

                        switch ($tax->type) {
                            case 'inclusive':
                                $inclusives[] = $tax;
                                break;
                            case 'compound':
                                $compounds[] = $tax;
                                break;
                            case 'fixed':
                                $item_tax_total += $tax->rate * $quantity;
                                break;
                            default:
                                $item_tax_amount = ($item_discounted_total / 100) * $tax->rate;

                                $item_tax_total += $item_tax_amount;
                                break;
                        }
                    }

                    if ($inclusives) {
                        $item_sub_and_tax_total = $item_discounted_total + $item_tax_total;

                        $item_base_rate = $item_sub_and_tax_total / (1 + collect($inclusives)->sum('rate') / 100);
                        $item_tax_total = $item_sub_and_tax_total - $item_base_rate;

                        $item_sub_total = $item_base_rate + $discount;
                    }

                    if ($compounds) {
                        foreach ($compounds as $compound) {
                            $item_tax_total += (($item_discounted_total + $item_tax_total) / 100) * $compound->rate;
                        }
                    }
                }

                $sub_total += $item_sub_total;
                $tax_total += $item_tax_total;

                $items[$key] = money($item_sub_total, $currency_code, true)->format();
            }
        }

        $json->items = $items;

        $json->sub_total = money($sub_total, $currency_code, true)->format();

        $json->discount_text = trans('invoices.add_discount');
        $json->discount_total = '';

        $json->tax_total = money($tax_total, $currency_code, true)->format();

        // Apply discount to total
        if ($discount) {
            $json->discount_text = trans('invoices.show_discount', ['discount' => $discount]);
            $json->discount_total = money($sub_total * ($discount / 100), $currency_code, true)->format();

            $sub_total = $sub_total - ($sub_total * ($discount / 100));
        }

        $grand_total = $sub_total + $tax_total;

        $json->grand_total = money($grand_total, $currency_code, true)->format();

        // Get currency object
        $currency = Currency::where('code', $currency_code)->first();

        $json->currency_name = $currency->name;
        $json->currency_code = $currency_code;
        $json->currency_rate = $currency->rate;

        $json->thousands_separator = $currency->thousands_separator;
        $json->decimal_mark = $currency->decimal_mark;
        $json->precision = (int) $currency->precision;
        $json->symbol_first = $currency->symbol_first;
        $json->symbol = $currency->symbol;

        return response()->json($json);
    }
}
