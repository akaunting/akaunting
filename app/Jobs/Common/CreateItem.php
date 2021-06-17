<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Jobs\Common\CreateItemTaxes;
use App\Models\Common\Item;

class CreateItem extends Job
{
    protected $item;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
        $this->request->merge(['created_by' => user_id()]);
    }

    /**
     * Execute the job.
     *
     * @return Item
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->item = Item::create($this->request->all());

            // Upload picture
            if ($this->request->file('picture')) {
                $media = $this->getMedia($this->request->file('picture'), 'items');

                $this->item->attachMedia($media, 'picture');
            }

            $this->dispatch(new CreateItemTaxes($this->item, $this->request));
        });

        return $this->item;
    }
}
