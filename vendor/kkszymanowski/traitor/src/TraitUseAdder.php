<?php

/*
 * KKSzymanowski/Traitor
 * Add a trait use statement to existing class
 *
 * @package KKSzymanowski/Traitor
 * @author Kuba Szymanowski <kuba.szymanowski@inf24.pl>
 * @link https://github.com/kkszymanowski/traitor
 * @license MIT
 */

namespace Traitor;

use ReflectionClass;
use RuntimeException;
use BadMethodCallException;
use Traitor\Handlers\AbstractTreeHandler;

class TraitUseAdder
{
    /** @var array */
    protected $traitReflections = [];

    /**
     * @param string $trait
     *
     * @return static
     */
    public function addTrait($trait)
    {
        return $this->addTraits([$trait]);
    }

    /**
     * @param array $traits
     *
     * @return static
     */
    public function addTraits(array $traits)
    {
        foreach ($traits as $trait) {
            $this->traitReflections[] = new ReflectionClass($trait);
        }

        return $this;
    }

    /**
     * @param string $class
     *
     * @throws BadMethodCallException
     * @throws RuntimeException
     *
     * @return $this
     */
    public function toClass($class)
    {
        if (count($this->traitReflections) == 0) {
            throw new BadMethodCallException("No traits to add were found. Call 'addTrait' first.");
        }

        $classReflection = new ReflectionClass($class);

        $filePath = $classReflection->getFileName();

        $content = file($filePath);

        foreach ($this->traitReflections as $traitReflection) {
            $handler = new AbstractTreeHandler(
                $content,
                $traitReflection->getName(),
                $classReflection->getName()
            );

            $content = $handler->handle()->toArray();
        }

        file_put_contents($filePath, implode($content));

        return $this;
    }
}
