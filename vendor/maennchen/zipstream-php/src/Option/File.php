<?php
declare(strict_types=1);

namespace ZipStream\Option;

use DateTime;

final class File
{
    /**
     * @var string
     */
    private $comment = '';
    /**
     * @var Method
     */
    private $method;
    /**
     * @var int
     */
    private $deflateLevel;
    /**
     * @var DateTime
     */
    private $time;
    /**
     * @var int
     */
    private $size = 0;

    public function defaultTo(Archive $archiveOptions): void
    {
        $this->deflateLevel = $this->deflateLevel ?: $archiveOptions->getDeflateLevel();
        $this->time = $this->time ?: new DateTime();
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return Method
     */
    public function getMethod(): Method
    {
        return $this->method ?: Method::DEFLATE();
    }

    /**
     * @param Method $method
     */
    public function setMethod(Method $method): void
    {
        $this->method = $method;
    }

    /**
     * @return int
     */
    public function getDeflateLevel(): int
    {
        return $this->deflateLevel ?: Archive::DEFAULT_DEFLATE_LEVEL;
    }

    /**
     * @param int $deflateLevel
     */
    public function setDeflateLevel(int $deflateLevel): void
    {
        $this->deflateLevel = $deflateLevel;
    }

    /**
     * @return DateTime
     */
    public function getTime(): DateTime
    {
        return $this->time;
    }

    /**
     * @param DateTime $time
     */
    public function setTime(DateTime $time): void
    {
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }
}
