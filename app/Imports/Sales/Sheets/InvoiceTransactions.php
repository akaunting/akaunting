<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Banking\Transaction as Request;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction as Model;
use App\Models\Common\Contact;
use App\Models\Sale\Invoice;
use App\Models\Setting\Category;

class InvoiceTransactions extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['type'] = 'income';

        if (empty($row['account_id']) && !empty($row['account_name'])) {
            $row['account_id'] = Account::firstOrCreate([
                'name'              => $row['account_name'],
            ], [
                'company_id'        => session('company_id'),
                'number'            => Account::max('number') + 1,
                'currency_code'     => setting('default.currency'),
                'opening_balance'   => 0,
                'enabled'           => 1,
            ])->id;
        }

        if (empty($row['account_id']) && !empty($row['account_number'])) {
            $row['account_id'] = Account::firstOrCreate([
                'number'            => $row['account_number'],
            ], [
                'company_id'        => session('company_id'),
                'name'              => $row['account_number'],
                'currency_code'     => setting('default.currency'),
                'opening_balance'   => 0,
                'enabled'           => 1,
            ])->id;
        }

        if (empty($row['account_id']) && !empty($row['currency_code'])) {
            $row['account_id'] = Account::firstOrCreate([
                'currency_code'     => $row['currency_code'],
            ], [
                'company_id'        => session('company_id'),
                'name'              => $row['currency_code'],
                'number'            => Account::max('number') + 1,
                'opening_balance'   => 0,
                'enabled'           => 1,
            ])->id;
        }

        if (empty($row['contact_id']) && !empty($row['contact_name'])) {
            $row['contact_id'] = Contact::firstOrCreate([
                'name'              => $row['contact_name'],
            ], [
                'company_id'        => session('company_id'),
                'type'              => 'customer',
                'currency_code'     => setting('default.currency'),
                'enabled'           => 1,
            ])->id;
        }

        if (empty($row['contact_id']) && !empty($row['contact_email'])) {
            $row['contact_id'] = Contact::firstOrCreate([
                'email'             => $row['contact_email'],
            ], [
                'company_id'        => session('company_id'),
                'type'              => 'customer',
                'name'              => $row['contact_email'],
                'currency_code'     => setting('default.currency'),
                'enabled'           => 1,
            ])->id;
        }

        if (empty($row['category_id']) && !empty($row['category_name'])) {
            $row['category_id'] = Category::firstOrCreate([
                'name'              => $row['category_name'],
            ], [
                'company_id'        => session('company_id'),
                'type'              => 'income',
                'color'             => '#' . dechex(rand(0x000000, 0xFFFFFF)),
                'enabled'           => 1,
            ])->id;
        }

        $row['document_id'] = Invoice::number($row['invoice_number'])->pluck('id')->first();

        return $row;
    }

    public function rules(): array
    {
        $rules = (new Request())->rules();

        $rules['invoice_number'] = 'required|string';

        return $rules;
    }
}
