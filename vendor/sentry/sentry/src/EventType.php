<?php

declare(strict_types=1);

namespace Sentry;

/**
 * This enum represents all the possible types of events that a Sentry server
 * supports.
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 */
final class EventType implements \Stringable
{
    /**
     * @var string The value of the enum instance
     */
    private $value;

    /**
     * @var array<string, self> A list of cached enum instances
     */
    private static $instances = [];

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Creates an instance of this enum for the "default" value.
     */
    public static function default(): self
    {
        return self::getInstance('default');
    }

    public static function event(): self
    {
        return self::getInstance('event');
    }

    /**
     * Creates an instance of this enum for the "transaction" value.
     */
    public static function transaction(): self
    {
        return self::getInstance('transaction');
    }

    public static function checkIn(): self
    {
        return self::getInstance('check_in');
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private static function getInstance(string $value): self
    {
        if (!isset(self::$instances[$value])) {
            self::$instances[$value] = new self($value);
        }

        return self::$instances[$value];
    }
}
