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

        $view->getFactory()->startPush('content_content_start', view('partials.read-only'));
    }
}
