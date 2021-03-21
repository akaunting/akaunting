<?php

namespace Http\Message\Decorator;

use Psr\Http\Message\StreamInterface;

/**
 * Decorates a stream.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait StreamDecorator
{
    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->stream->__toString();
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->stream->close();
    }

    /**
     * {@inheritdoc}
     */
    public function detach()
    {
        return $this->stream->detach();
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        return $this->stream->getSize();
    }

    /**
     * {@inheritdoc}
     */
    public function tell()
    {
        return $this->stream->tell();
    }

    /**
     * {@inheritdoc}
     */
    public function eof()
    {
        return $this->stream->eof();
    }

    /**
     * {@inheritdoc}
     */
    public function isSeekable()
    {
        return $this->stream->isSeekable();
    }

    /**
     * {@inheritdoc}
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        $this->stream->seek($offset, $whence);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->stream->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable()
    {
        return $this->stream->isWritable();
    }

    /**
     * {@inheritdoc}
     */
    public function write($string)
    {
        return $this->stream->write($string);
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable()
    {
        return $this->stream->isReadable();
    }

    /**
     * {@inheritdoc}
     */
    public function read($length)
    {
        return $this->stream->read($length);
    }

    /**
     * {@inheritdoc}
     */
    public function getContents()
    {
        return $this->stream->getContents();
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata($key = null)
    {
        return $this->stream->getMetadata($key);
    }
}
