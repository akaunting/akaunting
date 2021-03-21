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
 * Handler to list and open saved dataset
 */
class OpenHandler
{
    protected $debugBar;

    /**
     * @param DebugBar $debugBar
     * @throws DebugBarException
     */
    public function __construct(DebugBar $debugBar)
    {
        if (!$debugBar->isDataPersisted()) {
            throw new DebugBarException("DebugBar must have a storage backend to use OpenHandler");
        }
        $this->debugBar = $debugBar;
    }

    /**
     * Handles the current request
     *
     * @param array $request Request data
     * @param bool $echo
     * @param bool $sendHeader
     * @return string
     * @throws DebugBarException
     */
    public function handle($request = null, $echo = true, $sendHeader = true)
    {
        if ($request === null) {
            $request = $_REQUEST;
        }

        $op = 'find';
        if (isset($request['op'])) {
            $op = $request['op'];
            if (!in_array($op, array('find', 'get', 'clear'))) {
                throw new DebugBarException("Invalid operation '{$request['op']}'");
            }
        }

        if ($sendHeader) {
            $this->debugBar->getHttpDriver()->setHeaders(array(
                    'Content-Type' => 'application/json'
                ));
        }

        $response = json_encode(call_user_func(array($this, $op), $request));
        if ($echo) {
            echo $response;
        }
        return $response;
    }

    /**
     * Find operation
     * @param $request
     * @return array
     */
    protected function find($request)
    {
        $max = 20;
        if (isset($request['max'])) {
            $max = $request['max'];
        }

        $offset = 0;
        if (isset($request['offset'])) {
            $offset = $request['offset'];
        }

        $filters = array();
        foreach (array('utime', 'datetime', 'ip', 'uri', 'method') as $key) {
            if (isset($request[$key])) {
                $filters[$key] = $request[$key];
            }
        }

        return $this->debugBar->getStorage()->find($filters, $max, $offset);
    }

    /**
     * Get operation
     * @param $request
     * @return array
     * @throws DebugBarException
     */
    protected function get($request)
    {
        if (!isset($request['id'])) {
            throw new DebugBarException("Missing 'id' parameter in 'get' operation");
        }
        return $this->debugBar->getStorage()->get($request['id']);
    }

    /**
     * Clear operation
     */
    protected function clear($request)
    {
        $this->debugBar->getStorage()->clear();
        return array('success' => true);
    }
}
