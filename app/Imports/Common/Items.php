<?php

namespace App\Imports\Common;

use App\Abstracts\Import;
use App\Models\Common\Item as Model;
use App\Http\Requests\Common\Item as Request;
use App\Jobs\Common\CreateItem;

class Items extends Import
{
    public function model(array $row)
    {
        return new Model($row);
        //$request = (new Request())->merge($row);

        //return dispatch_now(new CreateItem($request));
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
