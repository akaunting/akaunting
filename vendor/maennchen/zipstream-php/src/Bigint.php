<?php
declare(strict_types=1);

namespace ZipStream;

use OverflowException;

class Bigint
{
    /**
     * @var int[]
     */
    private $bytes = [0, 0, 0, 0, 0, 0, 0, 0];

    /**
     * Initialize the bytes array
     *
     * @param int $value
     */
    public function __construct(int $value = 0)
    {
        $this->fillBytes($value, 0, 8);
    }

    /**
     * Fill the bytes field with int
     *
     * @param int $value
     * @param int $start
     * @param int $count
     * @return void
     */
    protected function fillBytes(int $value, int $start, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $this->bytes[$start + $i] = $i >= PHP_INT_SIZE ? 0 : $value & 0xFF;
            $value >>= 8;
        }
    }

    /**
     * Get an instance
     *
     * @param int $value
     * @return Bigint
     */
    public static function init(int $value = 0): self
    {
        return new self($value);
    }

    /**
     * Fill bytes from low to high
     *
     * @param int $low
     * @param int $high
     * @return Bigint
     */
    public static function fromLowHigh(int $low, int $high): self
    {
        $bigint = new Bigint();
        $bigint->fillBytes($low, 0, 4);
        $bigint->fillBytes($high, 4, 4);
        return $bigint;
    }

    /**
     * Get high 32
     *
     * @return int
     */
    public function getHigh32(): int
    {
        return $this->getValue(4, 4);
    }

    /**
     * Get value from bytes array
     *
     * @param int $end
     * @param int $length
     * @return int
     */
    public function getValue(int $end = 0, int $length = 8): int
    {
        $result = 0;
        for ($i = $end + $length - 1; $i >= $end; $i--) {
            $result <<= 8;
            $result |= $this->bytes[$i];
        }
        return $result;
    }

    /**
     * Get low FF
     *
     * @param bool $force
     * @return float
     */
    public function getLowFF(bool $force = false): float
    {
        if ($force || $this->isOver32()) {
            return (float)0xFFFFFFFF;
        }
        return (float)$this->getLow32();
    }

    /**
     * Check if is over 32
     *
     * @param bool $force
     * @return bool
     */
    public function isOver32(bool $force = false): bool
    {
        // value 0xFFFFFFFF already needs a Zip64 header
        return $force ||
            max(array_slice($this->bytes, 4, 4)) > 0 ||
            min(array_slice($this->bytes, 0, 4)) === 0xFF;
    }

    /**
     * Get low 32
     *
     * @return int
     */
    public function getLow32(): int
    {
        return $this->getValue(0, 4);
    }

    /**
     * Get hexadecimal
     *
     * @return string
     */
    public function getHex64(): string
    {
        $result = '0x';
        for ($i = 7; $i >= 0; $i--) {
            $result .= sprintf('%02X', $this->bytes[$i]);
        }
        return $result;
    }

    /**
     * Add
     *
     * @param Bigint $other
     * @return Bigint
     */
    public function add(Bigint $other): Bigint
    {
        $result = clone $this;
        $overflow = false;
        for ($i = 0; $i < 8; $i++) {
            $result->bytes[$i] += $other->bytes[$i];
            if ($overflow) {
                $result->bytes[$i]++;
                $overflow = false;
            }
            if ($result->bytes[$i] & 0x100) {
                $overflow = true;
                $result->bytes[$i] &= 0xFF;
            }
        }
        if ($overflow) {
            throw new OverflowException;
        }
        return $result;
    }
}
