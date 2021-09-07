<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Jobs\Common\CreateItemTaxes;
use App\Models\Common\Item;

class CreateItem extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Item
    {
        \DB::transaction(function () {
            $this->model = Item::create($this->request->all());

            // Upload picture
            if ($this->request->file('picture')) {
                $media = $this->getMedia($this->request->file('picture'), 'items');

                $this->model->attachMedia($media, 'picture');
            }

            $this->dispatch(new CreateItemTaxes($this->model, $this->request));
        });

        return $this->model;
    }
}
