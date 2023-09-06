<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Statistics;

use Exception;
use Illuminate\Routing\Router;

class NumberOfRoutes
{
    public function get(): int
    {
        try {
            return collect(app(Router::class)->getRoutes())->count();
        } catch (Exception $e) {
            return 0;
        }
    }
}
