<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2023 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Exception;

/**
 * A "type error" Exception for Psy.
 */
class TypeErrorException extends \Exception implements Exception
{
    private $rawMessage;

    /**
     * Constructor!
     *
     * @deprecated PsySH no longer wraps TypeErrors
     *
     * @param string          $message  (default: "")
     * @param int             $code     (default: 0)
     * @param \Throwable|null $previous (default: null)
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        $this->rawMessage = $message;
        $message = \preg_replace('/, called in .*?: eval\\(\\)\'d code/', '', $message);
        parent::__construct(\sprintf('TypeError: %s', $message), $code, $previous);
    }

    /**
     * Get the raw (unformatted) message for this error.
     */
    public function getRawMessage(): string
    {
        return $this->rawMessage;
    }

    /**
     * Create a TypeErrorException from a TypeError.
     *
     * @deprecated PsySH no longer wraps TypeErrors
     *
     * @param \TypeError $e
     */
    public static function fromTypeError(\TypeError $e): self
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }
}
