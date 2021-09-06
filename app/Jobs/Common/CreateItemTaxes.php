<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Common\Item;
use App\Models\Common\ItemTax;

class CreateItemTaxes extends Job implements ShouldCreate
{
    protected $item;

    protected $request;

    public function __construct(Item $item, $request)
    {
        $this->item = $item;
        $this->request = $request;

        parent::__construct($item, $request);
    }

    /**
     * Execute the job.
     *
     * @return mixed
     * @todo type hint after upgrading to PHP 8
     */
    public function handle()
    {
        // BC for 2.0 version
        if (!empty($this->request['tax_id'])) {
            $this->request['tax_ids'][] = $this->request['tax_id'];
        }

        if (empty($this->request['tax_ids'])) {
            return false;
        }

        \DB::transaction(function () {
            foreach ($this->request['tax_ids'] as $tax_id) {
                ItemTax::create([
                    'company_id' => $this->item->company_id,
                    'item_id' => $this->item->id,
                    'tax_id' => $tax_id,
                ]);
            }
        });

        return $this->item->taxes;
    }
}
