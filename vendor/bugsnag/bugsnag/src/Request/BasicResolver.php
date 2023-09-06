<?php

namespace Bugsnag\Request;

class BasicResolver implements ResolverInterface
{
    /**
     * Resolve the current request.
     *
     * @return \Bugsnag\Request\RequestInterface
     */
    public function resolve()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
                $params = static::getInputParams($_SERVER, $_GET, false);
            } else {
                $params = static::getInputParams($_SERVER, $_POST, true);
            }

            return new PhpRequest(
                $_SERVER,
                empty($_SESSION) ? [] : $_SESSION,
                empty($_COOKIE) ? [] : $_COOKIE,
                static::getRequestHeaders($_SERVER),
                $params
            );
        }

        if (PHP_SAPI === 'cli' && isset($_SERVER['argv'])) {
            return new ConsoleRequest($_SERVER['argv']);
        }

        return new NullRequest();
    }

    /**
     * Get the request headers.
     *
     * Note how we're caching this result for ever, across all instances.
     *
     * This is because PHP is natively only designed to process one request,
     * then shutdown. Some applications can be designed to handle multiple
     * requests using their own request objects, thus will need to implement
     * their own bugsnag request resolver.
     *
     * @param array $server the server variables
     *
     * @return array
     */
    protected static function getRequestHeaders(array $server)
    {
        static $headers;

        if ($headers !== null) {
            return $headers;
        }

        if (function_exists('getallheaders')) {
            return getallheaders();
        }

        $headers = [];

        foreach ($server as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            } elseif ($name === 'CONTENT_TYPE') {
                $headers['Content-Type'] = $value;
            } elseif ($name === 'CONTENT_LENGTH') {
                $headers['Content-Length'] = $value;
            }
        }

        return $headers;
    }

    /**
     * Get the input params.
     *
     * Note how we're caching this result for ever, across all instances.
     *
     * This is because the input stream can only be read once on PHP 5.5, and
     * PHP is natively only designed to process one request, then shutdown.
     * Some applications can be designed to handle multiple requests using
     * their own request objects, thus will need to implement their own bugsnag
     * request resolver.
     *
     * @param array $server           the server variables
     * @param array $params           the array of parameters for this request type
     * @param bool  $fallbackToInput  if true, uses input when params is null
     *
     * @return array|null
     */
    protected static function getInputParams(array $server, array $params, $fallbackToInput = false)
    {
        static $result;

        if ($result !== null) {
            return $result ?: null;
        }

        $result = $params;

        if ($fallbackToInput === true) {
            $result = $result ?: static::parseInput($server, static::readInput());
        }

        return $result ?: null;
    }

    /**
     * Read the PHP input stream.
     *
     * @return string|false
     */
    protected static function readInput()
    {
        return file_get_contents('php://input') ?: false;
    }

    /**
     * Parse the given input string.
     *
     * @param array       $server the server variables
     * @param string|null $input  the http request input
     *
     * @return array|null
     */
    protected static function parseInput(array $server, $input)
    {
        if (!$input) {
            return null;
        }

        if (isset($server['CONTENT_TYPE']) && stripos($server['CONTENT_TYPE'], 'application/json') === 0) {
            return (array) json_decode($input, true) ?: null;
        }

        if (strtoupper($server['REQUEST_METHOD']) === 'PUT') {
            parse_str($input, $params);

            return (array) $params ?: null;
        }

        return null;
    }
}
