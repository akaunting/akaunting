<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Sale\Invoice as Request;
use App\Models\Common\Contact;
use App\Models\Sale\Invoice as Model;
use App\Models\Setting\Category;

class Invoices extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        if (empty($row['contact_id']) && !empty($row['contact_name'])) {
            $row['contact_id'] = Contact::firstOrCreate([
                'name'          => $row['contact_name'],
            ], [
                'company_id'    => session('company_id'),
                'type'          => 'customer',
                'currency_code' => setting('default.currency'),
                'enabled'       => 1,
            ])->id;
        }

        if (empty($row['contact_id']) && !empty($row['contact_email'])) {
            $row['contact_id'] = Contact::firstOrCreate([
                'email'         => $row['contact_email'],
            ], [
                'company_id'    => session('company_id'),
                'type'          => 'customer',
                'name'          => $row['contact_email'],
                'currency_code' => setting('default.currency'),
                'enabled'       => 1,
            ])->id;
        }

        if (empty($row['category_id']) && !empty($row['category_name'])) {
            $row['category_id'] = Category::firstOrCreate([
                'name'          => $row['category_name'],
            ], [
                'company_id'    => session('company_id'),
                'type'          => 'income',
                'color'         => '#' . dechex(rand(0x000000, 0xFFFFFF)),
                'enabled'       => 1,
            ])->id;
        }

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
