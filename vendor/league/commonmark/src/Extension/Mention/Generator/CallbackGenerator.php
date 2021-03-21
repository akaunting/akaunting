<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Extension\Mention\Generator;

use League\CommonMark\Extension\Mention\Mention;
use League\CommonMark\Inline\Element\AbstractInline;

final class CallbackGenerator implements MentionGeneratorInterface
{
    /**
     * A callback function which sets the URL on the passed mention and returns the mention, return a new AbstractInline based object or null if the mention is not a match
     *
     * @var callable(Mention): ?AbstractInline
     */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function generateMention(Mention $mention): ?AbstractInline
    {
        $result = \call_user_func_array($this->callback, [$mention]);
        if ($result === null) {
            return null;
        }

        if ($result instanceof AbstractInline && !($result instanceof Mention)) {
            return $result;
        }

        if ($result instanceof Mention && $result->hasUrl()) {
            return $mention;
        }

        throw new \RuntimeException('CallbackGenerator callable must set the URL on the passed mention and return the mention, return a new AbstractInline based object or null if the mention is not a match');
    }
}
