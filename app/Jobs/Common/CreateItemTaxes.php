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
     * @return mixed
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
