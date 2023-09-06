<?php

namespace Staudenmeir\EloquentHasManyDeep\Eloquent;

class CompositeKey
{
    public array $columns;

    public function __construct(...$columns)
    {
        $this->columns = $columns;
    }
}
