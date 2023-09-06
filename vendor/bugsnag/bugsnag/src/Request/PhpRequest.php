<?php

namespace Bugsnag\Request;

class PhpRequest implements RequestInterface
{
    /**
     * The server variables.
     *
     * @var array
     */
    protected $server;

    /**
     * The session variables.
     *
     * @var array
     */
    protected $session;

    /**
     * The cookie variables.
     *
     * @var array
     */
    protected $cookies;

    /**
     * The http headers.
     *
     * @var array
     */
    protected $headers;

    /**
     * The input params.
     *
     * @var array|null
     */
    protected $input;

    /**
     * Create a new php request instance.
     *
     * @param array      $server  the server variables
     * @param array      $session the session variables
     * @param array      $cookies the cookie variables
     * @param array      $headers the http headers
     * @param array|null $input   the input params
     *
     * @return void
     */
    public function __construct(array $server, array $session, array $cookies, array $headers, array $input = null)
    {
        $this->server = $server;
        $this->session = $session;
        $this->cookies = $cookies;
        $this->headers = $headers;
        $this->input = $input;
    }

    /**
     * Are we currently processing a request?
     *
     * @return bool
     */
    public function isRequest()
    {
        return true;
    }

    /**
     * Get the session data.
     *
     * @return array
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Get the cookies.
     *
     * @return array
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Get the request formatted as meta data.
     *
     * @return array
     */
    public function getMetaData()
    {
        $data = [];

        $data['url'] = $this->getCurrentUrl();

        if (isset($this->server['REQUEST_METHOD'])) {
            $data['httpMethod'] = $this->server['REQUEST_METHOD'];
        }

        $data['params'] = $this->input;

        $data['clientIp'] = $this->getRequestIp();

        if (isset($this->server['HTTP_USER_AGENT'])) {
            $data['userAgent'] = $this->server['HTTP_USER_AGENT'];
        }

        if ($this->headers) {
            $data['headers'] = $this->headers;
        }

        return ['request' => $data];
    }

    /**
     * Get the request context.
     *
     * @return string|null
     */
    public function getContext()
    {
        if (isset($this->server['REQUEST_METHOD']) && isset($this->server['REQUEST_URI'])) {
            return $this->server['REQUEST_METHOD'].' '.strtok($this->server['REQUEST_URI'], '?');
        }

        return null;
    }

    /**
     * Get the request user id.
     *
     * @return string|null
     */
    public function getUserId()
    {
        return $this->getRequestIp();
    }

    /**
     * Get the request url.
     *
     * @return string
     */
    protected function getCurrentUrl()
    {
        $schema = ((!empty($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off') || (!empty($this->server['SERVER_PORT']) && $this->server['SERVER_PORT'] == 443)) ? 'https://' : 'http://';

        $host = isset($this->server['HTTP_HOST']) ? $this->server['HTTP_HOST'] : 'localhost';

        return $schema.$host.$this->server['REQUEST_URI'];
    }

    /**
     * Get the request ip.
     *
     * @return string|null
     */
    protected function getRequestIp()
    {
        if (isset($this->server['HTTP_X_FORWARDED_FOR'])) {
            return $this->server['HTTP_X_FORWARDED_FOR'];
        }

        if (isset($this->server['REMOTE_ADDR'])) {
            return $this->server['REMOTE_ADDR'];
        }

        return null;
    }
}
