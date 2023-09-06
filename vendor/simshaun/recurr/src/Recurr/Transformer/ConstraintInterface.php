<?php

/*
 * Copyright 2014 Shaun Simmons
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Recurr\Transformer;

interface ConstraintInterface
{
    /**
     * @return bool
     */
    public function stopsTransformer();

    /**
     * @param \DateTimeInterface $date
     *
     * @return bool
     */
    public function test(\DateTimeInterface $date);
}
