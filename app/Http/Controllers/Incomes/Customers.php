<?php

namespace App\Http\Controllers\Incomes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Income\Customer as Request;
use App\Models\Auth\User;
use App\Models\Income\Customer;
use App\Models\Setting\Currency;
use App\Utilities\ImportFile;

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

        return view('incomes.customers.index', compact('customers', 'emails'));
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
            if (empty($request['email'])) {
                $request['email'] = '';
            }

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
        $rows = $import->all();

        foreach ($rows as $row) {
            $data = $row->toArray();

            if (empty($data['email'])) {
                $data['email'] = '';
            }

            $data['company_id'] = session('company_id');

            Customer::create($data);
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
            if (empty($request['email'])) {
                $request['email'] = '';
            }

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

    public function currency()
    {
        $customer_id = request('customer_id');

        $customer = Customer::find($customer_id);

        return response()->json($customer);
    }

    public function customer(Request $request)
    {
        if (empty($request['email'])) {
            $request['email'] = '';
        }

        $customer = Customer::create($request->all());

        return response()->json($customer);
    }
}
