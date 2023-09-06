<?php

namespace Bugsnag\Request;

class NullRequest implements RequestInterface
{
    /**
     * Are we currently processing a request?
     *
     * @return bool
     */
    public function isRequest()
    {
        return false;
    }

    /**
     * Get the session data.
     *
     * @return array
     */
    public function getSession()
    {
        return [];
    }

    /**
     * Get the cookies.
     *
     * @return array
     */
    public function getCookies()
    {
        return [];
    }

    /**
     * Get the request formatted as meta data.
     *
     * @return array
     */
    public function getMetaData()
    {
        return [];
    }

    /**
     * Get the request context.
     *
     * @return string|null
     */
    public function getContext()
    {
        return null;
    }

    /**
     * Get the request user id.
     *
     * @return string|null
     */
    public function getUserId()
    {
        return null;
    }
}
