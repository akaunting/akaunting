<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Sale\Invoice as Request;
use App\Models\Sale\Invoice as Model;

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
            $row['contact_id'] = $this->getContactIdFromName($row, 'customer');
        }

        if (empty($row['contact_id']) && !empty($row['contact_email'])) {
            $row['contact_id'] = $this->getContactIdFromEmail($row, 'customer');
        }

        if (empty($row['category_id']) && !empty($row['category_name'])) {
            $row['category_id'] = $this->getCategoryIdFromName($row, 'income');
        }

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
