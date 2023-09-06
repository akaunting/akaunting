<?php

/*
 * This file is part of Crawler Detect - the web crawler detection library.
 *
 * (c) Mark Beech <m@rkbee.ch>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jaybizzle\CrawlerDetect\Fixtures;

abstract class AbstractProvider
{
    /**
     * The data set.
     *
     * @var array
     */
    protected $data;

    /**
     * Return the data set.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->data;
    }
}
