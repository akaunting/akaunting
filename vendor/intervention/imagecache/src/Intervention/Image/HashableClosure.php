<?php

namespace Intervention\Image;

use Closure;
use Opis\Closure\SerializableClosure;

class HashableClosure
{
    /**
     * Original closure
     *
     * @var \Opis\Closure\SerializableClosure
     */
    protected $closure;

    /**
     * Create new instance
     *
     * @param Closure $closure
     */
    public function __construct(Closure $closure)
    {
        $this->setClosure($closure);
    }

    /**
     * Set closure for hashing
     *
     * @param Closure $closure
     */
    public function setClosure(Closure $closure)
    {
        $closure = new SerializableClosure($closure);
        $closure->removeSecurityProvider();

        $this->closure = $closure;

        return $this;
    }

    /**
     * Get current closure
     *
     * @return \Opis\Closure\SerializableClosure
     */
    public function getClosure()
    {
        return $this->closure;
    }

    /**
     * Get hash of current closure
     *
     * This method uses "opis/closure" to serialize the closure. "opis/closure",
     * however, adds a identifier by "spl_object_hash" to each serialize
     * call, making it impossible to create unique hashes. This method
     * removes this identifier and builds the hash afterwards.
     *
     * @return string
     */
    public function getHash()
    {
        $data = unserialize($this->closure->serialize());

        unset($data['self']); // unset identifier added by spl_object_hash

        return md5(serialize($data));
    }
}
