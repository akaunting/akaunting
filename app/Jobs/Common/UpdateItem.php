<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Jobs\Common\CreateItemTaxes;
use App\Models\Common\Item;

class UpdateItem extends Job
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
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Item
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->item->update($this->request->all());

            // Upload picture
            if ($this->request->file('picture')) {
                $media = $this->getMedia($this->request->file('picture'), 'items');

                $this->item->attachMedia($media, 'picture');
            }

            $this->deleteRelationships($this->item, ['taxes']);

            $this->dispatch(new CreateItemTaxes($this->item, $this->request));
        });

        return $this->item;
    }
}
