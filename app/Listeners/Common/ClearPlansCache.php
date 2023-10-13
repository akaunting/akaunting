<?php

namespace App\Listeners\Common;

use App\Events\Auth\UserDeleted;
use App\Events\Common\CompanyDeleted;
use App\Traits\Plans;
use Illuminate\Events\Dispatcher;

class ClearPlansCache
{
    use Plans;

    public function handle($event): void
    {
        $this->clearPlansCache();
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            UserDeleted::class      => 'handle',
            CompanyDeleted::class   => 'handle',
        ];
    }
}
