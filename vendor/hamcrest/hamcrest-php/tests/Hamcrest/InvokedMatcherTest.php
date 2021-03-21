<?php
namespace Hamcrest;

use PHPUnit\Framework\TestCase;

class SampleInvokeMatcher extends BaseMatcherTest
{
    private $matchAgainst;

    public function __construct($matchAgainst)
    {
        $this->matchAgainst = $matchAgainst;
    }

    public function matches($item)
    {
        return $item == $this->matchAgainst;
    }

}

class InvokedMatcherTest extends TestCase
{
    public function testInvokedMatchersCallMatches()
    {
        $sampleMatcher = new SampleInvokeMatcher('foo');

        $this->assertTrue($sampleMatcher('foo'));
        $this->assertFalse($sampleMatcher('bar'));
    }
}
