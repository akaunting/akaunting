<?php

/*
 * This file is part of the League\Fractal package.
 *
 * (c) Phil Sturgeon <me@philsturgeon.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Fractal\Resource;

/**
 * Null Resource
 *
 * The Null Resource represents a resource that doesn't exist. This can be
 * useful to indicate that a certain relationship is null in some output
 * formats (e.g. JSON API), which require even a relationship that is null at
 * the moment to be listed.
 */
class NullResource extends ResourceAbstract
{
    /**
     * Get the data.
     *
     * @return mixed
     */
    public function getData()
    {
        // Null has no data associated with it.
    }
}
