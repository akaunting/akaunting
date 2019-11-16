<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
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
        $this->item->update($this->request->all());

        // Upload picture
        if ($this->request->file('picture')) {
            $media = $this->getMedia($this->request->file('picture'), 'items');

            $this->item->attachMedia($media, 'picture');
        }

        return $this->item;
    }
}
