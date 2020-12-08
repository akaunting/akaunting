<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Common\ItemTax;

class CreateItemTaxes extends Job
{
    protected $item;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $item
     * @param  $request
     */
    public function __construct($item, $request)
    {
        $this->item = $item;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return Item
     */
    public function handle()
    {
        \DB::transaction(function () {
            // This controller for BC < 2.1
            if (!empty($this->request['tax_id'])) {
                ItemTax::create([
                    'company_id' => $this->item->company_id,
                    'item_id' => $this->item->id,
                    'tax_id' => $this->request['tax_id']
                ]);
            } else {
                foreach ($this->request['tax_ids'] as $tax_id) {
                    ItemTax::create([
                        'company_id' => $this->item->company_id,
                        'item_id' => $this->item->id,
                        'tax_id' => $tax_id
                    ]);
                }
            }
        });

        return $this->item->taxes;
    }
}
