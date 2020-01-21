<?php

namespace App\Imports\Sales;

use App\Abstracts\Import;
use App\Http\Requests\Banking\Transaction as Request;
use App\Models\Banking\Transaction as Model;

class Revenues extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['type'] = 'income';
        $row['account_id'] = $this->getAccountId($row);
        $row['category_id'] = $this->getCategoryId($row, 'income');
        $row['contact_id'] = $this->getContactId($row, 'customer');
        $row['document_id'] = $this->getDocumentId($row);

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
