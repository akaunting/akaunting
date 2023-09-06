<?php

declare(strict_types=1);

namespace ParaTest\JUnit;

/**
 * @internal
 *
 * @immutable
 */
final class TestCaseWithMessage extends TestCase
{
    public function __construct(
        string $name,
        string $class,
        string $file,
        int $line,
        int $assertions,
        float $time,
        public readonly ?string $type,
        public readonly string $text,
        public readonly MessageType $xmlTagName
    ) {
        parent::__construct($name, $class, $file, $line, $assertions, $time);
    }
}
