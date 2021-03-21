<?php

namespace Http\Message;

use Psr\Http\Message\StreamInterface;

/**
 * Factory for PSR-7 Stream.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface StreamFactory
{
    /**
     * Creates a new PSR-7 stream.
     *
     * @param string|resource|StreamInterface|null $body
     *
     * @return StreamInterface
     *
     * @throws \InvalidArgumentException If the stream body is invalid.
     * @throws \RuntimeException         If creating the stream from $body fails. 
     */
    public function createStream($body = null);
}
