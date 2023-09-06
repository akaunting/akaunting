<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebugBar\DataCollector;

/**
 * DataCollector Interface
 */
interface DataCollectorInterface
{
    /**
     * Called by the DebugBar when data needs to be collected
     *
     * @return array Collected data
     */
    function collect();

    /**
     * Returns the unique name of the collector
     *
     * @return string
     */
    function getName();
}
