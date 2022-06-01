<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class ReadOnlyNotification
{
    public function compose(View $view): void
    {
        if (! config('read-only.enabled')) {
            return;
        }

        $notifications = $view->getData()['notifications'];

        $notifications[] = view('components.read-only');

        $view->with([
            'notifications' => $notifications,
        ]);
    }
}
