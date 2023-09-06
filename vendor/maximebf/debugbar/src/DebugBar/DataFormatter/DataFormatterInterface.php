<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebugBar\DataFormatter;

/**
 * Formats data to be outputed as string
 */
interface DataFormatterInterface
{
    /**
     * Transforms a PHP variable to a string representation
     *
     * @param mixed $data
     * @return string
     */
    function formatVar($data);

    /**
     * Transforms a duration in seconds in a readable string
     *
     * @param float $seconds
     * @return string
     */
    function formatDuration($seconds);

    /**
     * Transforms a size in bytes to a human readable string
     *
     * @param string $size
     * @param integer $precision
     * @return string
     */
    function formatBytes($size, $precision = 2);
}
