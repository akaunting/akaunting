<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Common\Item;

class CreateItem extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Item
     */
    public function handle()
    {
        $item = Item::create($this->request->all());

        // Upload picture
        if ($this->request->file('picture')) {
            $media = $this->getMedia($this->request->file('picture'), 'items');

            $item->attachMedia($media, 'picture');
        }

        return $item;
    }
}
