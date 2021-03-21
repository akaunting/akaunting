<?php

use Akaunting\Money\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    public function testFactoryMethods()
    {
        $this->assertEquals(Currency::USD(), new Currency('USD'));
        $this->assertEquals(Currency::TRY(), new Currency('TRY'));
    }

    public function testCantInstantiateUnknownCurrency()
    {
        $this->expectException(OutOfBoundsException::class);

        new Currency('unknown');
    }

    public function testComparison()
    {
        $c1 = new Currency('USD');
        $c2 = new Currency('TRY');

        $this->assertTrue($c1->equals(new Currency('USD')));
        $this->assertTrue($c2->equals(new Currency('TRY')));
        $this->assertFalse($c1->equals($c2));
        $this->assertFalse($c2->equals($c1));
    }

    public function testGetters()
    {
        $c1 = new Currency('USD');

        $this->assertEquals('USD', $c1->getCurrency());
        $this->assertEquals('US Dollar', $c1->getName());
        $this->assertEquals(840, $c1->getCode());
        $this->assertEquals(2, $c1->getPrecision());
        $this->assertEquals(100, $c1->getSubunit());
        $this->assertEquals('$', $c1->getSymbol());
        $this->assertEquals(true, $c1->isSymbolFirst());
        $this->assertEquals('.', $c1->getDecimalMark());
        $this->assertEquals(',', $c1->getThousandsSeparator());
        $this->assertEquals('$', $c1->getPrefix());
        $this->assertEquals('', $c1->getSuffix());
        $this->assertNotEmpty($c1->toArray()['USD']);
        $this->assertJson($c1->toJson());
        $this->assertNotEmpty($c1->jsonSerialize()['USD']);

        $c2 = new Currency('CDF');
        $this->assertEquals('CDF', $c2->getCurrency());
        $this->assertEquals('Congolese Franc', $c2->getName());
        $this->assertEquals(976, $c2->getCode());
        $this->assertEquals(2, $c2->getPrecision());
        $this->assertEquals(100, $c2->getSubunit());
        $this->assertEquals('Fr', $c2->getSymbol());
        $this->assertEquals(false, $c2->isSymbolFirst());
        $this->assertEquals('.', $c2->getDecimalMark());
        $this->assertEquals(',', $c2->getThousandsSeparator());
        $this->assertEquals('', $c2->getPrefix());
        $this->assertEquals(' Fr', $c2->getSuffix());
        $this->assertNotEmpty($c2->toArray()['CDF']);
        $this->assertJson($c2->toJson());
        $this->assertNotEmpty($c2->jsonSerialize()['CDF']);
    }

    public function testToString()
    {
        $this->assertEquals('USD (US Dollar)', (string) new Currency('USD'));
        $this->assertEquals('TRY (Turkish Lira)', (string) new Currency('TRY'));
    }

    public function testResetCurrencies()
    {
        $currencies = Currency::getCurrencies();
        Currency::setCurrencies([]);
        $this->assertEmpty(Currency::getCurrencies());
        Currency::setCurrencies($currencies);
    }
}
