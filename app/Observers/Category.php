<?php

namespace App\Observers;

use App\Abstracts\Observer;
use App\Jobs\Setting\DeleteCategory;
use App\Models\Setting\Category as Model;
use App\Traits\Jobs;

class Category extends Observer
{
    use Jobs;

    /**
     * Listen to the deleted event.
     *
     * @param  Model  $category
     * @return void
     */
    public function deleting(Model $category)
    {
        foreach ($category->sub_categories as $sub_category) {
            $this->dispatch(new DeleteCategory($sub_category));
        }
    }
}
