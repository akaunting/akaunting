<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebugBar;

interface RequestIdGeneratorInterface
{
    /**
     * Generates a unique id for the current request.  If called repeatedly, a new unique id must
     * always be returned on each call to generate() - even across different object instances.
     *
     * To avoid any potential confusion in ID --> value maps, the returned value must be
     * guaranteed to not be all-numeric.
     *
     * @return string
     */
    function generate();
}
