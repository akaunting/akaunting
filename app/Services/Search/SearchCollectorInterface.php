<?php

namespace App\Services\Search;

interface SearchCollectorInterface
{
    public function collectByKeyword(string $keyword): array;
}
