<?php

namespace App\Imports\Sales\Customers\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Common\Contact as Request;
use App\Models\Common\Contact as Model;

class Customers extends Import
{
    public $request_class = Request::class;

    public $model = Model::class;

    public $columns = [
        'type',
        'name',
        'email',
    ];

    public function model(array $row)
    {
        if (self::hasRow($row)) {
            return;
        }

        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        // Uploaded file may not include a country column.
        $country = ! empty($row['country']) ? array_search($row['country'], trans('countries')) : false;

        $row['type'] = 'customer';
        $row['country'] = !empty($country) ? $country : null;
        $row['category_id'] = $this->getCategoryId($row, 'income');
        $row['currency_code'] = $this->getCurrencyCode($row);
        $row['user_id'] = null;

        // The export writes can_login as true or false, and isset() held for false too: every customer with an
        // e-mail looked a user up, and one that cannot log in was linked to a user of the same e-mail.
        // The id alone is read: a user model reads its companies when it is retrieved.
        if (filter_var($row['can_login'] ?? false, FILTER_VALIDATE_BOOLEAN) && ! empty($row['email'])) {
            // Only a customer user of this company, the e-mail of an employee or of another company's user is not linked
            $user_id = user_model_class()::where('email', $row['email'])
                ->isCustomer()
                ->whereHas('companies', fn ($query) => $query->where('companies.id', company_id()))
                ->toBase()
                ->value('users.id');

            $row['user_id'] = $user_id ? (int) $user_id : null;
        }

        return $row;
    }
}
