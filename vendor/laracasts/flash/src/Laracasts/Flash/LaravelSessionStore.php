<?php

namespace Laracasts\Flash;

use Illuminate\Session\Store;

class LaravelSessionStore implements SessionStore
{
    /**
     * @var Store
     */
    private $session;

    /**
     * Create a new session store instance.
     *
     * @param Store $session
     */
    function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Flash a message to the session.
     *
     * @param string $name
     * @param array  $data
     */
    public function flash($name, $data)
    {
        $this->session->flash($name, $data);
    }
}
