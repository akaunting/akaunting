<?php

namespace Http\Message\Encoding;

use Clue\StreamFilter as Filter;
use Psr\Http\Message\StreamInterface;

/**
 * Stream for encoding to gzip format (RFC 1952).
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
class GzipEncodeStream extends FilteredStream
{
    /**
     * @param int $level
     */
    public function __construct(StreamInterface $stream, $level = -1)
    {
        if (!extension_loaded('zlib')) {
            throw new \RuntimeException('The zlib extension must be enabled to use this stream');
        }

        parent::__construct($stream, ['window' => 31, 'level' => $level]);

        // @deprecated will be removed in 2.0
        $this->writeFilterCallback = Filter\fun($this->writeFilter(), ['window' => 31]);
    }

    /**
     * {@inheritdoc}
     */
    protected function readFilter(): string
    {
        return 'zlib.deflate';
    }

    /**
     * {@inheritdoc}
     */
    protected function writeFilter(): string
    {
        return 'zlib.inflate';
    }
}
