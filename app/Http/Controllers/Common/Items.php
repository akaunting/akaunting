<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Exports\Common\Items as Export;
use App\Http\Requests\Common\Item as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Common\Items as Import;
use App\Jobs\Common\CreateItem;
use App\Jobs\Common\DeleteItem;
use App\Jobs\Common\UpdateItem;
use App\Models\Common\Item;
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
        $items = Item::with('category', 'media', 'taxes')->collect();

        return $this->response('common.items.index', compact('items'));
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
        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        return view('common.items.create', compact('taxes'));
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

            $message = trans('messages.success.created', ['type' => trans_choice('general.items', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('items.create');

            $message = $response['message'];

            flash($message)->error()->important();
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
        $response = $this->importExcel(new Import, $request, trans_choice('general.items', 2));

        if ($response['success']) {
            $response['redirect'] = route('items.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['common', 'items']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
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
        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        return view('common.items.edit', compact('item', 'taxes'));
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

            flash($message)->error()->important();
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

            flash($message)->error()->important();
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
        return $this->exportExcel(new Export, trans_choice('general.items', 2));
    }

    public function autocomplete()
    {
        $type = request('type');
        $query = request('query');
        $currency_code = request('currency_code');

        if (empty($currency_code) || (strtolower($currency_code)  == 'null')) {
            $currency_code = default_currency();
        }

        $autocomplete = Item::autocomplete([
            'name' => $query
        ]);

        // Eager load taxes and tax relationships to prevent N+1 queries
        $items = $autocomplete->with(['taxes.tax'])->get();

        if ($items) {
            foreach ($items as $item) {
                $item_price = ($type == 'bill') ? $item->purchase_price : $item->sale_price;
                $item_tax_price = 0;

                if ($item->taxes->count()) {
                    $inclusives = $compounds = [];

                    foreach($item->taxes as $item_tax) {
                        // Tax relationship is now eager loaded, preventing N+1 query
                        $tax = $item_tax->tax;

                        switch ($tax->type) {
                            case 'inclusive':
                                $inclusives[] = $tax;
                                break;
                            case 'compound':
                                $compounds[] = $tax;
                                break;
                            case 'fixed':
                                $item_tax_price += $tax->rate;
                                break;
                            case 'withholding':
                                $item_tax_amount = 0 - $item_price * ($tax->rate / 100);

                                $item_tax_price += $item_tax_amount;
                                break;
                            default:
                                $item_tax_amount = ($item_price / 100) * $tax->rate;

                                $item_tax_price += $item_tax_amount;
                                break;
                        }
                    }

                    if ($inclusives) {
                        $item_base_rate = $item_price / (1 + collect($inclusives)->sum('rate') / 100);
                        $item_tax_price = $item_price - $item_base_rate;
                    }

                    if ($compounds) {
                        foreach ($compounds as $compound) {
                            $item_tax_price += ($item_tax_price / 100) * $compound->rate;
                        }
                    }
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

                $item->total = $total;
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
}
