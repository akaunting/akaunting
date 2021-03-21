<?php

namespace Monooso\Unobserve;

use BadMethodCallException;

class Proxy
{
    /** @var object */
    private $target;

    /** @var array */
    private $events;

    public function __construct($target, array $events = [])
    {
        $this->target = $target;
        $this->events = $events;
    }

    public function __call($name, $arguments)
    {
        if ($this->isMuted($name)) {
            return null;
        }

        if (method_exists($this->target, $name)) {
            return $this->target->$name(...$arguments);
        }

        throw new BadMethodCallException(sprintf(
            'Unknown method [%s@%s]',
            get_class($this->target),
            $name
        ));
    }

    protected function isMuted($name): bool
    {
        return (in_array('*', $this->events) || in_array($name, $this->events));
    }
}
