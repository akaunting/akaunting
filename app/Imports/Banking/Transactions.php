<?php

namespace App\Imports\Banking;

use App\Abstracts\Import;
use App\Events\Common\ModelCreated;
use App\Http\Requests\Banking\Transaction as Request;
use App\Models\Banking\Transaction as Model;

class Transactions extends Import
{
    public function model(array $row)
    {
        $model = new Model($row);

        event(new ModelCreated($model, $row));

        return $model;
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['account_id'] = $this->getAccountId($row);
        $row['category_id'] = $this->getCategoryId($row);
        $row['contact_id'] = $this->getContactId($row);
        $row['document_id'] = $this->getDocumentId($row);

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
