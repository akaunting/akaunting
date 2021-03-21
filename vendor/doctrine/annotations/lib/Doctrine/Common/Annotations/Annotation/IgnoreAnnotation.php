<?php

namespace Doctrine\Common\Annotations\Annotation;

use RuntimeException;

use function is_array;
use function is_string;
use function json_encode;
use function sprintf;

/**
 * Annotation that can be used to signal to the parser to ignore specific
 * annotations during the parsing process.
 *
 * @Annotation
 */
final class IgnoreAnnotation
{
    /** @phpstan-var list<string> */
    public $names;

    /**
     * @throws RuntimeException
     *
     * @phpstan-param array{value: string|list<string>} $values
     */
    public function __construct(array $values)
    {
        if (is_string($values['value'])) {
            $values['value'] = [$values['value']];
        }

        if (! is_array($values['value'])) {
            throw new RuntimeException(sprintf(
                '@IgnoreAnnotation expects either a string name, or an array of strings, but got %s.',
                json_encode($values['value'])
            ));
        }

        $this->names = $values['value'];
    }
}
