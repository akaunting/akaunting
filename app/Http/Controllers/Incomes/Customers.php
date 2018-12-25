<?php

namespace App\Http\Controllers\Incomes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Income\Customer as Request;
use App\Models\Auth\User;
use App\Models\Income\Customer;
use App\Models\Income\Invoice;
use App\Models\Income\Revenue;
use App\Models\Setting\Currency;
use App\Utilities\Import;
use App\Utilities\ImportFile;
use Date;
use Illuminate\Http\Request as FRequest;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Customers extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $customers = Customer::collect();

        return view('incomes.customers.index', compact('customers'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Customer  $customer
     *
     * @return Response
     */
    public function show(Customer $customer)
    {
        $amounts = [
            'paid' => 0,
            'open' => 0,
            'overdue' => 0,
        ];

        $counts = [
            'invoices' => 0,
            'revenues' => 0,
        ];

        // Handle invoices
        $invoices = Invoice::with(['status', 'payments'])->where('customer_id', $customer->id)->get();

        $counts['invoices'] = $invoices->count();

        $invoice_payments = [];

        $today = Date::today()->toDateString();

        foreach ($invoices as $item) {
            $payments = 0;

            foreach ($item->payments as $payment) {
                $payment->category = $item->category;

                $invoice_payments[] = $payment;

                $amount = $payment->getConvertedAmount();

                $amounts['paid'] += $amount;

                $payments += $amount;
            }

            if ($item->invoice_status_code == 'paid') {
                continue;
            }

            // Check if it's open or overdue invoice
            if ($item->due_at > $today) {
                $amounts['open'] += $item->getConvertedAmount() - $payments;
            } else {
                $amounts['overdue'] += $item->getConvertedAmount() - $payments;
            }
        }

        // Handle revenues
        $revenues = Revenue::with(['account', 'category'])->where('customer_id', $customer->id)->get();

        $counts['revenues'] = $revenues->count();

        // Prepare data
        $items = collect($revenues)->each(function ($item) use (&$amounts) {
            $amounts['paid'] += $item->getConvertedAmount();
        });

        $limit = request('limit', setting('general.list_limit', '25'));
        $transactions = $this->paginate($items->merge($invoice_payments)->sortByDesc('paid_at'), $limit);
        $invoices = $this->paginate($invoices->sortByDesc('paid_at'), $limit);
        $revenues = $this->paginate($revenues->sortByDesc('paid_at'), $limit);

        return view('incomes.customers.show', compact('customer', 'counts', 'amounts', 'transactions', 'invoices', 'revenues'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $currencies = Currency::enabled()->pluck('name', 'code');

        return view('incomes.customers.create', compact('currencies'));
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
        if (empty($request->input('create_user'))) {
            Customer::create($request->all());
        } else {
            // Check if user exist
            $user = User::where('email', $request['email'])->first();
            if (!empty($user)) {
                $message = trans('messages.error.customer', ['name' => $user->name]);

                flash($message)->error();

                return redirect()->back()->withInput($request->except('create_user'))->withErrors(
                    ['email' => trans('customers.error.email')]
                );
            }

            // Create user first
            $data = $request->all();
            $data['locale'] = setting('general.default_locale', 'en-GB');

            $user = User::create($data);
            $user->roles()->attach(['3']);
            $user->companies()->attach([session('company_id')]);

            // Finally create customer
            $request['user_id'] = $user->id;

            Customer::create($request->all());
        }

        $message = trans('messages.success.added', ['type' => trans_choice('general.customers', 1)]);

        flash($message)->success();

        return redirect('incomes/customers');
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Customer  $customer
     *
     * @return Response
     */
    public function duplicate(Customer $customer)
    {
        $clone = $customer->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.customers', 1)]);

        flash($message)->success();

        return redirect('incomes/customers/' . $clone->id . '/edit');
    }

    /**
     * Import the specified resource.
     *
     * @param  ImportFile  $import
     *
     * @return Response
     */
    public function import(ImportFile $import)
    {
        if (!Import::createFromFile($import, 'Income\Customer')) {
            return redirect('common/import/incomes/customers');
        }

        $message = trans('messages.success.imported', ['type' => trans_choice('general.customers', 2)]);

        flash($message)->success();

        return redirect('incomes/customers');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Customer  $customer
     *
     * @return Response
     */
    public function edit(Customer $customer)
    {
        $currencies = Currency::enabled()->pluck('name', 'code');

        return view('incomes.customers.edit', compact('customer', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Customer  $customer
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Customer $customer, Request $request)
    {
        if (empty($request->input('create_user'))) {
            $customer->update($request->all());
        } else {
            // Check if user exist
            $user = User::where('email', $request['email'])->first();
            if (!empty($user)) {
                $message = trans('messages.error.customer', ['name' => $user->name]);

                flash($message)->error();

                return redirect()->back()->withInput($request->except('create_user'))->withErrors(
                    ['email' => trans('customers.error.email')]
                );
            }

            // Create user first
            $user = User::create($request->all());
            $user->roles()->attach(['3']);
            $user->companies()->attach([session('company_id')]);

            $request['user_id'] = $user->id;

            $customer->update($request->all());
        }

        $message = trans('messages.success.updated', ['type' => trans_choice('general.customers', 1)]);

        flash($message)->success();

        return redirect('incomes/customers');
    }

    /**
     * Enable the specified resource.
     *
     * @param  Customer  $customer
     *
     * @return Response
     */
    public function enable(Customer $customer)
    {
        $customer->enabled = 1;
        $customer->save();

        $message = trans('messages.success.enabled', ['type' => trans_choice('general.customers', 1)]);

        flash($message)->success();

        return redirect()->route('customers.index');
    }

    /**
     * Disable the specified resource.
     *
     * @param  Customer  $customer
     *
     * @return Response
     */
    public function disable(Customer $customer)
    {
        $customer->enabled = 0;
        $customer->save();

        $message = trans('messages.success.disabled', ['type' => trans_choice('general.customers', 1)]);

        flash($message)->success();

        return redirect()->route('customers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Customer  $customer
     *
     * @return Response
     */
    public function destroy(Customer $customer)
    {
        $relationships = $this->countRelationships($customer, [
            'invoices' => 'invoices',
            'revenues' => 'revenues',
        ]);

        if (empty($relationships)) {
            $customer->delete();

            $message = trans('messages.success.deleted', ['type' => trans_choice('general.customers', 1)]);

            flash($message)->success();
        } else {
            $message = trans('messages.warning.deleted', ['name' => $customer->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();
        }

        return redirect('incomes/customers');
    }

    /**
     * Export the specified resource.
     *
     * @return Response
     */
    public function export()
    {
        \Excel::create('customers', function($excel) {
            $excel->sheet('customers', function($sheet) {
                $sheet->fromModel(Customer::filter(request()->input())->get()->makeHidden([
                    'id', 'company_id', 'created_at', 'updated_at', 'deleted_at'
                ]));
            });
        })->download('xlsx');
    }

    public function currency()
    {
        $customer_id = (int) request('customer_id');

        if (empty($customer_id)) {
            return response()->json([]);
        }

        $customer = Customer::find($customer_id);

        if (empty($customer)) {
            return response()->json([]);
        }

        $currency_code = setting('general.default_currency');

        if (isset($customer->currency_code)) {
            $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

            if (array_key_exists($customer->currency_code, $currencies)) {
                $currency_code = $customer->currency_code;
            }
        }

        // Get currency object
        $currency = Currency::where('code', $currency_code)->first();

        $customer->currency_name = $currency->name;
        $customer->currency_code = $currency_code;
        $customer->currency_rate = $currency->rate;

        $customer->thousands_separator = $currency->thousands_separator;
        $customer->decimal_mark = $currency->decimal_mark;
        $customer->precision = (int) $currency->precision;
        $customer->symbol_first = $currency->symbol_first;
        $customer->symbol = $currency->symbol;

        return response()->json($customer);
    }

    public function customer(Request $request)
    {
        $customer = Customer::create($request->all());

        return response()->json($customer);
    }

    public function field(FRequest $request)
    {
        $html = '';

        if ($request['fields']) {
            foreach ($request['fields'] as $field) {
                switch ($field) {
                    case 'password':
                        $html .= \Form::passwordGroup('password', trans('auth.password.current'), 'key', [], null, 'col-md-6 password');
                        break;
                    case 'password_confirmation':
                        $html .= \Form::passwordGroup('password_confirmation', trans('auth.password.current_confirm'), 'key', [], null, 'col-md-6 password');
                        break;
                }
            }
        }

        $json = [
            'html' => $html
        ];

        return response()->json($json);
    }

    /**
     * Generate a pagination collection.
     *
     * @param array|Collection      $items
     * @param int   $perPage
     * @param int   $page
     * @param array $options
     *
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
