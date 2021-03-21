<?php
declare(strict_types=1);

namespace BigintTest;

use OverflowException;
use PHPUnit\Framework\TestCase;
use ZipStream\Bigint;

class BigintTest extends TestCase
{
    public function testConstruct(): void
    {
        $bigint = new Bigint(0x12345678);
        $this->assertSame('0x0000000012345678', $bigint->getHex64());
        $this->assertSame(0x12345678, $bigint->getLow32());
        $this->assertSame(0, $bigint->getHigh32());
    }

    public function testConstructLarge(): void
    {
        $bigint = new Bigint(0x87654321);
        $this->assertSame('0x0000000087654321', $bigint->getHex64());
        $this->assertSame('87654321', bin2hex(pack('N', $bigint->getLow32())));
        $this->assertSame(0, $bigint->getHigh32());
    }

    public function testAddSmallValue(): void
    {
        $bigint = new Bigint(1);
        $bigint = $bigint->add(Bigint::init(2));
        $this->assertSame(3, $bigint->getLow32());
        $this->assertFalse($bigint->isOver32());
        $this->assertTrue($bigint->isOver32(true));
        $this->assertSame($bigint->getLowFF(), (float)$bigint->getLow32());
        $this->assertSame($bigint->getLowFF(true), (float)0xFFFFFFFF);
    }

    public function testAddWithOverflowAtLowestByte(): void
    {
        $bigint = new Bigint(0xFF);
        $bigint = $bigint->add(Bigint::init(0x01));
        $this->assertSame(0x100, $bigint->getLow32());
    }

    public function testAddWithOverflowAtInteger32(): void
    {
        $bigint = new Bigint(0xFFFFFFFE);
        $this->assertFalse($bigint->isOver32());
        $bigint = $bigint->add(Bigint::init(0x01));
        $this->assertTrue($bigint->isOver32());
        $bigint = $bigint->add(Bigint::init(0x01));
        $this->assertSame('0x0000000100000000', $bigint->getHex64());
        $this->assertTrue($bigint->isOver32());
        $this->assertSame((float)0xFFFFFFFF, $bigint->getLowFF());
    }

    public function testAddWithOverflowAtInteger64(): void
    {
        $bigint = Bigint::fromLowHigh(0xFFFFFFFF, 0xFFFFFFFF);
        $this->assertSame('0xFFFFFFFFFFFFFFFF', $bigint->getHex64());
        $this->expectException(OverflowException::class);
        $bigint->add(Bigint::init(1));
    }
}
