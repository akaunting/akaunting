<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebugBar\Storage;

interface StorageInterface
{
    /**
     * Saves collected data
     *
     * @param string $id
     * @param string $data
     */
    function save($id, $data);

    /**
     * Returns collected data with the specified id
     *
     * @param string $id
     * @return array
     */
    function get($id);

    /**
     * Returns a metadata about collected data
     *
     * @param array $filters
     * @param integer $max
     * @param integer $offset
     * @return array
     */
    function find(array $filters = array(), $max = 20, $offset = 0);

    /**
     * Clears all the collected data
     */
    function clear();
}
