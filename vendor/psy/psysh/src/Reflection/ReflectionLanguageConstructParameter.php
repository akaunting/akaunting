<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2023 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Reflection;

/**
 * A fake ReflectionParameter but for language construct parameters.
 *
 * It stubs out all the important bits and returns whatever was passed in $opts.
 */
class ReflectionLanguageConstructParameter extends \ReflectionParameter
{
    private $function;
    private $parameter;
    private $opts;

    public function __construct($function, $parameter, array $opts)
    {
        $this->function = $function;
        $this->parameter = $parameter;
        $this->opts = $opts;
    }

    /**
     * No class here.
     *
     * @todo remove \ReturnTypeWillChange attribute after dropping support for PHP 7.0 (when we can use nullable types)
     */
    #[\ReturnTypeWillChange]
    public function getClass()
    {
        return;
    }

    /**
     * Is the param an array?
     *
     * @return bool
     */
    public function isArray(): bool
    {
        return \array_key_exists('isArray', $this->opts) && $this->opts['isArray'];
    }

    /**
     * Get param default value.
     *
     * @todo remove \ReturnTypeWillChange attribute after dropping support for PHP 7.x (when we can use mixed type)
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function getDefaultValue()
    {
        if ($this->isDefaultValueAvailable()) {
            return $this->opts['defaultValue'];
        }

        return null;
    }

    /**
     * Get param name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->parameter;
    }

    /**
     * Is the param optional?
     *
     * @return bool
     */
    public function isOptional(): bool
    {
        return \array_key_exists('isOptional', $this->opts) && $this->opts['isOptional'];
    }

    /**
     * Does the param have a default value?
     *
     * @return bool
     */
    public function isDefaultValueAvailable(): bool
    {
        return \array_key_exists('defaultValue', $this->opts);
    }

    /**
     * Is the param passed by reference?
     *
     * (I don't think this is true for anything we need to fake a param for)
     *
     * @return bool
     */
    public function isPassedByReference(): bool
    {
        return \array_key_exists('isPassedByReference', $this->opts) && $this->opts['isPassedByReference'];
    }
}
