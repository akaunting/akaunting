<?php

namespace Intervention\Image\Test;

use Intervention\Image\HashableClosure;
use Opis\Closure\SerializableClosure;
use PHPUnit\Framework\TestCase;

class HashableClosureTest extends TestCase
{
    public function testConstructor()
    {
        $hashable = new HashableClosure(function () {
            return 'foo';
        });

        $this->assertInstanceOf(HashableClosure::class, $hashable);
    }

    public function testSetGetClosure()
    {
        $hashable = new HashableClosure(function () {
            return 'foo';
        });

        $result = $hashable->setClosure(function () {
            return 'bar';
        });

        $this->assertInstanceOf(HashableClosure::class, $result);
        $this->assertInstanceOf(SerializableClosure::class, $hashable->getClosure());
    }

    public function testGetHash()
    {
        $hashable1 = new HashableClosure(function ($test) {
            $test->set('foo');
            $test->test(30);
        });
        $hashable2 = new HashableClosure(function ($test) {
            $test->set('foo');
            $test->test(30);
        });
        $hashable3 = new HashableClosure(function ($test) {
            $test->set('foo');
            $test->test(31);
        });

        $this->assertEquals($hashable1->getHash(), $hashable2->getHash());
        $this->assertNotEquals($hashable1->getHash(), $hashable3->getHash());
        $this->assertNotEquals($hashable2->getHash(), $hashable3->getHash());
    }
}
