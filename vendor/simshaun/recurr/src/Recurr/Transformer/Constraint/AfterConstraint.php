<?php

/*
 * Copyright 2014 Shaun Simmons
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Recurr\Transformer\Constraint;

use Recurr\Transformer\Constraint;

class AfterConstraint extends Constraint
{

    protected $stopsTransformer = false;

    /** @var \DateTimeInterface */
    protected $after;

    /** @var bool */
    protected $inc;

    /**
     * @param \DateTimeInterface $after
     * @param bool               $inc Include date if it equals $after.
     */
    public function __construct(\DateTimeInterface $after, $inc = false)
    {
        $this->after = $after;
        $this->inc    = $inc;
    }

    /**
     * Passes if $date is after $after
     *
     * {@inheritdoc}
     */
    public function test(\DateTimeInterface $date)
    {
        if ($this->inc) {
            return $date >= $this->after;
        }

        return $date > $this->after;
    }
}
