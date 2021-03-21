<?php

/*
 * Copyright 2014 Shaun Simmons
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Recurr\Transformer;

abstract class Constraint implements ConstraintInterface
{

    protected $stopsTransformer = true;

    public function stopsTransformer()
    {
        return $this->stopsTransformer;
    }
}