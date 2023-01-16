<?php

namespace App\Listeners\Setting;

use App\Events\Setting\CategoryDeleted as Event;
use App\Jobs\Setting\DeleteCategory;
use App\Traits\Jobs;

class DeleteCategoryDeletedSubCategories
{
    use Jobs;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $category = $event->category;

        if (empty($category->sub_categories)) {
            return;
        }

        foreach ($category->sub_categories as $sub_category) {
            $this->dispatch(new DeleteCategory($sub_category));
        }
    }
}
