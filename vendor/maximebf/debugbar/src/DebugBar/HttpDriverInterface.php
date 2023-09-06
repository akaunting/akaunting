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

/**
 * Provides an abstraction of PHP native features for easier integration
 * in third party frameworks
 */
interface HttpDriverInterface
{
    /**
     * Sets HTTP headers
     *
     * @param array $headers
     * @return
     */
    function setHeaders(array $headers);

    /**
     * Checks if the session is started
     *
     * @return boolean
     */
    function isSessionStarted();

    /**
     * Sets a value in the session
     *
     * @param string $name
     * @param string $value
     */
    function setSessionValue($name, $value);

    /**
     * Checks if a value is in the session
     *
     * @param string $name
     * @return boolean
     */
    function hasSessionValue($name);

    /**
     * Returns a value from the session
     *
     * @param string $name
     * @return mixed
     */
    function getSessionValue($name);

    /**
     * Deletes a value from the session
     *
     * @param string $name
     */
    function deleteSessionValue($name);
}
