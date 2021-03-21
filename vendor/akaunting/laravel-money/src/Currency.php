<?php

namespace Akaunting\Money;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use JsonSerializable;
use OutOfBoundsException;

/**
 * Class Currency.
 *
 * @method static Currency AED()
 * @method static Currency AFN()
 * @method static Currency ALL()
 * @method static Currency AMD()
 * @method static Currency ANG()
 * @method static Currency AOA()
 * @method static Currency ARS()
 * @method static Currency AUD()
 * @method static Currency AWG()
 * @method static Currency AZN()
 * @method static Currency BAM()
 * @method static Currency BBD()
 * @method static Currency BDT()
 * @method static Currency BGN()
 * @method static Currency BHD()
 * @method static Currency BIF()
 * @method static Currency BMD()
 * @method static Currency BND()
 * @method static Currency BOB()
 * @method static Currency BOV()
 * @method static Currency BRL()
 * @method static Currency BSD()
 * @method static Currency BTN()
 * @method static Currency BWP()
 * @method static Currency BYR()
 * @method static Currency BZD()
 * @method static Currency CAD()
 * @method static Currency CDF()
 * @method static Currency CHF()
 * @method static Currency CLF()
 * @method static Currency CLP()
 * @method static Currency CNY()
 * @method static Currency COP()
 * @method static Currency CRC()
 * @method static Currency CUC()
 * @method static Currency CUP()
 * @method static Currency CVE()
 * @method static Currency CZK()
 * @method static Currency DJF()
 * @method static Currency DKK()
 * @method static Currency DOP()
 * @method static Currency DZD()
 * @method static Currency EGP()
 * @method static Currency ERN()
 * @method static Currency ETB()
 * @method static Currency EUR()
 * @method static Currency FJD()
 * @method static Currency FKP()
 * @method static Currency GBP()
 * @method static Currency GEL()
 * @method static Currency GHS()
 * @method static Currency GIP()
 * @method static Currency GMD()
 * @method static Currency GNF()
 * @method static Currency GTQ()
 * @method static Currency GYD()
 * @method static Currency HKD()
 * @method static Currency HNL()
 * @method static Currency HRK()
 * @method static Currency HTG()
 * @method static Currency HUF()
 * @method static Currency IDR()
 * @method static Currency ILS()
 * @method static Currency INR()
 * @method static Currency IQD()
 * @method static Currency IRR()
 * @method static Currency ISK()
 * @method static Currency JMD()
 * @method static Currency JOD()
 * @method static Currency JPY()
 * @method static Currency KES()
 * @method static Currency KGS()
 * @method static Currency KHR()
 * @method static Currency KMF()
 * @method static Currency KPW()
 * @method static Currency KRW()
 * @method static Currency KWD()
 * @method static Currency KYD()
 * @method static Currency KZT()
 * @method static Currency LAK()
 * @method static Currency LBP()
 * @method static Currency LKR()
 * @method static Currency LRD()
 * @method static Currency LSL()
 * @method static Currency LTL()
 * @method static Currency LVL()
 * @method static Currency LYD()
 * @method static Currency MAD()
 * @method static Currency MDL()
 * @method static Currency MGA()
 * @method static Currency MKD()
 * @method static Currency MMK()
 * @method static Currency MNT()
 * @method static Currency MOP()
 * @method static Currency MRO()
 * @method static Currency MUR()
 * @method static Currency MVR()
 * @method static Currency MWK()
 * @method static Currency MXN()
 * @method static Currency MYR()
 * @method static Currency MZN()
 * @method static Currency NAD()
 * @method static Currency NGN()
 * @method static Currency NIO()
 * @method static Currency NOK()
 * @method static Currency NPR()
 * @method static Currency NZD()
 * @method static Currency OMR()
 * @method static Currency PAB()
 * @method static Currency PEN()
 * @method static Currency PGK()
 * @method static Currency PHP()
 * @method static Currency PKR()
 * @method static Currency PLN()
 * @method static Currency PYG()
 * @method static Currency QAR()
 * @method static Currency RON()
 * @method static Currency RSD()
 * @method static Currency RUB()
 * @method static Currency RWF()
 * @method static Currency SAR()
 * @method static Currency SBD()
 * @method static Currency SCR()
 * @method static Currency SDG()
 * @method static Currency SEK()
 * @method static Currency SGD()
 * @method static Currency SHP()
 * @method static Currency SLL()
 * @method static Currency SOS()
 * @method static Currency SRD()
 * @method static Currency SSP()
 * @method static Currency STD()
 * @method static Currency SVC()
 * @method static Currency SYP()
 * @method static Currency SZL()
 * @method static Currency THB()
 * @method static Currency TJS()
 * @method static Currency TMT()
 * @method static Currency TND()
 * @method static Currency TOP()
 * @method static Currency TRY()
 * @method static Currency TTD()
 * @method static Currency TWD()
 * @method static Currency TZS()
 * @method static Currency UAH()
 * @method static Currency UGX()
 * @method static Currency USD()
 * @method static Currency UYU()
 * @method static Currency UZS()
 * @method static Currency VEF()
 * @method static Currency VND()
 * @method static Currency VUV()
 * @method static Currency WST()
 * @method static Currency XAF()
 * @method static Currency XAG()
 * @method static Currency XAU()
 * @method static Currency XCD()
 * @method static Currency XDR()
 * @method static Currency XOF()
 * @method static Currency XPF()
 * @method static Currency YER()
 * @method static Currency ZAR()
 * @method static Currency ZMW()
 * @method static Currency ZWL()
 */
class Currency implements Arrayable, Jsonable, JsonSerializable, Renderable
{
    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var float
     */
    protected $rate;

    /**
     * @var int
     */
    protected $precision;

    /**
     * @var int
     */
    protected $subunit;

    /**
     * @var string
     */
    protected $symbol;

    /**
     * @var bool
     */
    protected $symbolFirst;

    /**
     * @var string
     */
    protected $decimalMark;

    /**
     * @var string
     */
    protected $thousandsSeparator;

    /**
     * @var array
     */
    protected static $currencies;

    /**
     * Create a new instance.
     *
     * @param string $currency
     *
     * @throws \OutOfBoundsException
     */
    public function __construct($currency)
    {
        $currency = strtoupper(trim($currency));
        $currencies = static::getCurrencies();

        if (!array_key_exists($currency, $currencies)) {
            throw new OutOfBoundsException('Invalid currency "' . $currency . '"');
        }

        $attributes = $currencies[$currency];
        $this->currency = $currency;
        $this->name = (string) $attributes['name'];
        $this->code = (int) $attributes['code'];
        $this->rate = (float) isset($attributes['rate']) ? $attributes['rate'] : 1;
        $this->precision = (int) $attributes['precision'];
        $this->subunit = (int) $attributes['subunit'];
        $this->symbol = (string) $attributes['symbol'];
        $this->symbolFirst = (bool) $attributes['symbol_first'];
        $this->decimalMark = (string) $attributes['decimal_mark'];
        $this->thousandsSeparator = (string) $attributes['thousands_separator'];
    }

    /**
     * __callStatic.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return \Akaunting\Money\Currency
     */
    public static function __callStatic($method, array $arguments)
    {
        return new static($method, $arguments);
    }

    /**
     * setCurrencies.
     *
     * @param array $currencies
     *
     * @return void
     */
    public static function setCurrencies(array $currencies)
    {
        static::$currencies = $currencies;
    }

    /**
     * getCurrencies.
     *
     * @return array
     */
    public static function getCurrencies()
    {
        if (!isset(static::$currencies)) {
            static::$currencies = require __DIR__ . '/Config/money.php';
        }

        return (array) static::$currencies;
    }

    /**
     * equals.
     *
     * @param \Akaunting\Money\Currency $currency
     *
     * @return bool
     */
    public function equals(self $currency)
    {
        return $this->getCurrency() === $currency->getCurrency();
    }

    /**
     * getCurrency.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * getName.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * getCode.
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * getRate.
     *
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * getPrecision.
     *
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * getSubunit.
     *
     * @return int
     */
    public function getSubunit()
    {
        return $this->subunit;
    }

    /**
     * getSymbol.
     *
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * isSymbolFirst.
     *
     * @return bool
     */
    public function isSymbolFirst()
    {
        return $this->symbolFirst;
    }

    /**
     * getDecimalMark.
     *
     * @return string
     */
    public function getDecimalMark()
    {
        return $this->decimalMark;
    }

    /**
     * getThousandsSeparator.
     *
     * @return string
     */
    public function getThousandsSeparator()
    {
        return $this->thousandsSeparator;
    }

    /**
     * getPrefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        if (!$this->symbolFirst) {
            return '';
        }

        return $this->symbol;
    }

    /**
     * getSuffix.
     *
     * @return string
     */
    public function getSuffix()
    {
        if ($this->symbolFirst) {
            return '';
        }

        return ' ' . $this->symbol;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [$this->currency => [
            'name'                => $this->name,
            'code'                => $this->code,
            'rate'                => $this->rate,
            'precision'           => $this->precision,
            'subunit'             => $this->subunit,
            'symbol'              => $this->symbol,
            'symbol_first'        => $this->symbolFirst,
            'decimal_mark'        => $this->decimalMark,
            'thousands_separator' => $this->thousandsSeparator,
            'prefix'              => $this->getPrefix(),
            'suffix'              => $this->getSuffix(),
        ]];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * jsonSerialize.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return $this->currency . ' (' . $this->name . ')';
    }

    /**
     * __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
