<?php

namespace Bugsnag;

class Pipeline
{
    /**
     * The array of pipes to pass through.
     *
     * @var callable[]
     */
    protected $pipes;

    /**
     * Create a new basic pipeline instance.
     *
     * @param callable[] $pipes the array of pipes to pass through
     *
     * @return void
     */
    public function __construct(array $pipes = [])
    {
        $this->pipes = $pipes;
    }

    /**
     * Append the given pipe to the pipeline.
     *
     * @param callable $pipe a new pipe to pass through
     *
     * @return $this
     */
    public function pipe(callable $pipe)
    {
        $this->pipes[] = $pipe;

        return $this;
    }

    /**
     * Add a pipe to the pipeline before a given class.
     *
     * @param callable $pipe a new pipe to pass through
     * @param string $beforeClass to class to insert the pipe before
     *
     * @return $this
     */
    public function insertBefore(callable $pipe, $beforeClass)
    {
        $beforePosition = null;
        foreach ($this->pipes as $index => $callable) {
            $class = get_class($callable);
            if ($class === $beforeClass) {
                $beforePosition = $index;
                break;
            }
        }
        if ($beforePosition === null) {
            $this->pipes[] = $pipe;
        } else {
            array_splice($this->pipes, $beforePosition, 0, [$pipe]);
        }

        return $this;
    }

    /**
     * Run the pipeline.
     *
     * @param mixed    $passable    the item to send through the pipeline
     * @param callable $destination the final distination callback
     *
     * @return mixed
     */
    public function execute($passable, callable $destination)
    {
        $first = function ($passable) use ($destination) {
            return call_user_func($destination, $passable);
        };

        $pipes = array_reverse($this->pipes);

        return call_user_func(array_reduce($pipes, $this->getSlice(), $first), $passable);
    }

    /**
     * Get the closure that represents a slice.
     *
     * @return \Closure
     */
    protected function getSlice()
    {
        return function ($stack, $pipe) {
            return function ($passable) use ($stack, $pipe) {
                return call_user_func($pipe, $passable, $stack);
            };
        };
    }
}
