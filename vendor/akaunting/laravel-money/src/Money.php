<?php

namespace Akaunting\Money;

use BadFunctionCallException;
use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use InvalidArgumentException;
use JsonSerializable;
use OutOfBoundsException;
use UnexpectedValueException;

/**
 * Class Money.
 *
 * @method static Money AED(mixed $amount, bool $convert = false)
 * @method static Money AFN(mixed $amount, bool $convert = false)
 * @method static Money ALL(mixed $amount, bool $convert = false)
 * @method static Money AMD(mixed $amount, bool $convert = false)
 * @method static Money ANG(mixed $amount, bool $convert = false)
 * @method static Money AOA(mixed $amount, bool $convert = false)
 * @method static Money ARS(mixed $amount, bool $convert = false)
 * @method static Money AUD(mixed $amount, bool $convert = false)
 * @method static Money AWG(mixed $amount, bool $convert = false)
 * @method static Money AZN(mixed $amount, bool $convert = false)
 * @method static Money BAM(mixed $amount, bool $convert = false)
 * @method static Money BBD(mixed $amount, bool $convert = false)
 * @method static Money BDT(mixed $amount, bool $convert = false)
 * @method static Money BGN(mixed $amount, bool $convert = false)
 * @method static Money BHD(mixed $amount, bool $convert = false)
 * @method static Money BIF(mixed $amount, bool $convert = false)
 * @method static Money BMD(mixed $amount, bool $convert = false)
 * @method static Money BND(mixed $amount, bool $convert = false)
 * @method static Money BOB(mixed $amount, bool $convert = false)
 * @method static Money BOV(mixed $amount, bool $convert = false)
 * @method static Money BRL(mixed $amount, bool $convert = false)
 * @method static Money BSD(mixed $amount, bool $convert = false)
 * @method static Money BTN(mixed $amount, bool $convert = false)
 * @method static Money BWP(mixed $amount, bool $convert = false)
 * @method static Money BYR(mixed $amount, bool $convert = false)
 * @method static Money BZD(mixed $amount, bool $convert = false)
 * @method static Money CAD(mixed $amount, bool $convert = false)
 * @method static Money CDF(mixed $amount, bool $convert = false)
 * @method static Money CHF(mixed $amount, bool $convert = false)
 * @method static Money CLF(mixed $amount, bool $convert = false)
 * @method static Money CLP(mixed $amount, bool $convert = false)
 * @method static Money CNY(mixed $amount, bool $convert = false)
 * @method static Money COP(mixed $amount, bool $convert = false)
 * @method static Money CRC(mixed $amount, bool $convert = false)
 * @method static Money CUC(mixed $amount, bool $convert = false)
 * @method static Money CUP(mixed $amount, bool $convert = false)
 * @method static Money CVE(mixed $amount, bool $convert = false)
 * @method static Money CZK(mixed $amount, bool $convert = false)
 * @method static Money DJF(mixed $amount, bool $convert = false)
 * @method static Money DKK(mixed $amount, bool $convert = false)
 * @method static Money DOP(mixed $amount, bool $convert = false)
 * @method static Money DZD(mixed $amount, bool $convert = false)
 * @method static Money EGP(mixed $amount, bool $convert = false)
 * @method static Money ERN(mixed $amount, bool $convert = false)
 * @method static Money ETB(mixed $amount, bool $convert = false)
 * @method static Money EUR(mixed $amount, bool $convert = false)
 * @method static Money FJD(mixed $amount, bool $convert = false)
 * @method static Money FKP(mixed $amount, bool $convert = false)
 * @method static Money GBP(mixed $amount, bool $convert = false)
 * @method static Money GEL(mixed $amount, bool $convert = false)
 * @method static Money GHS(mixed $amount, bool $convert = false)
 * @method static Money GIP(mixed $amount, bool $convert = false)
 * @method static Money GMD(mixed $amount, bool $convert = false)
 * @method static Money GNF(mixed $amount, bool $convert = false)
 * @method static Money GTQ(mixed $amount, bool $convert = false)
 * @method static Money GYD(mixed $amount, bool $convert = false)
 * @method static Money HKD(mixed $amount, bool $convert = false)
 * @method static Money HNL(mixed $amount, bool $convert = false)
 * @method static Money HRK(mixed $amount, bool $convert = false)
 * @method static Money HTG(mixed $amount, bool $convert = false)
 * @method static Money HUF(mixed $amount, bool $convert = false)
 * @method static Money IDR(mixed $amount, bool $convert = false)
 * @method static Money ILS(mixed $amount, bool $convert = false)
 * @method static Money INR(mixed $amount, bool $convert = false)
 * @method static Money IQD(mixed $amount, bool $convert = false)
 * @method static Money IRR(mixed $amount, bool $convert = false)
 * @method static Money ISK(mixed $amount, bool $convert = false)
 * @method static Money JMD(mixed $amount, bool $convert = false)
 * @method static Money JOD(mixed $amount, bool $convert = false)
 * @method static Money JPY(mixed $amount, bool $convert = false)
 * @method static Money KES(mixed $amount, bool $convert = false)
 * @method static Money KGS(mixed $amount, bool $convert = false)
 * @method static Money KHR(mixed $amount, bool $convert = false)
 * @method static Money KMF(mixed $amount, bool $convert = false)
 * @method static Money KPW(mixed $amount, bool $convert = false)
 * @method static Money KRW(mixed $amount, bool $convert = false)
 * @method static Money KWD(mixed $amount, bool $convert = false)
 * @method static Money KYD(mixed $amount, bool $convert = false)
 * @method static Money KZT(mixed $amount, bool $convert = false)
 * @method static Money LAK(mixed $amount, bool $convert = false)
 * @method static Money LBP(mixed $amount, bool $convert = false)
 * @method static Money LKR(mixed $amount, bool $convert = false)
 * @method static Money LRD(mixed $amount, bool $convert = false)
 * @method static Money LSL(mixed $amount, bool $convert = false)
 * @method static Money LTL(mixed $amount, bool $convert = false)
 * @method static Money LVL(mixed $amount, bool $convert = false)
 * @method static Money LYD(mixed $amount, bool $convert = false)
 * @method static Money MAD(mixed $amount, bool $convert = false)
 * @method static Money MDL(mixed $amount, bool $convert = false)
 * @method static Money MGA(mixed $amount, bool $convert = false)
 * @method static Money MKD(mixed $amount, bool $convert = false)
 * @method static Money MMK(mixed $amount, bool $convert = false)
 * @method static Money MNT(mixed $amount, bool $convert = false)
 * @method static Money MOP(mixed $amount, bool $convert = false)
 * @method static Money MRO(mixed $amount, bool $convert = false)
 * @method static Money MUR(mixed $amount, bool $convert = false)
 * @method static Money MVR(mixed $amount, bool $convert = false)
 * @method static Money MWK(mixed $amount, bool $convert = false)
 * @method static Money MXN(mixed $amount, bool $convert = false)
 * @method static Money MYR(mixed $amount, bool $convert = false)
 * @method static Money MZN(mixed $amount, bool $convert = false)
 * @method static Money NAD(mixed $amount, bool $convert = false)
 * @method static Money NGN(mixed $amount, bool $convert = false)
 * @method static Money NIO(mixed $amount, bool $convert = false)
 * @method static Money NOK(mixed $amount, bool $convert = false)
 * @method static Money NPR(mixed $amount, bool $convert = false)
 * @method static Money NZD(mixed $amount, bool $convert = false)
 * @method static Money OMR(mixed $amount, bool $convert = false)
 * @method static Money PAB(mixed $amount, bool $convert = false)
 * @method static Money PEN(mixed $amount, bool $convert = false)
 * @method static Money PGK(mixed $amount, bool $convert = false)
 * @method static Money PHP(mixed $amount, bool $convert = false)
 * @method static Money PKR(mixed $amount, bool $convert = false)
 * @method static Money PLN(mixed $amount, bool $convert = false)
 * @method static Money PYG(mixed $amount, bool $convert = false)
 * @method static Money QAR(mixed $amount, bool $convert = false)
 * @method static Money RON(mixed $amount, bool $convert = false)
 * @method static Money RSD(mixed $amount, bool $convert = false)
 * @method static Money RUB(mixed $amount, bool $convert = false)
 * @method static Money RWF(mixed $amount, bool $convert = false)
 * @method static Money SAR(mixed $amount, bool $convert = false)
 * @method static Money SBD(mixed $amount, bool $convert = false)
 * @method static Money SCR(mixed $amount, bool $convert = false)
 * @method static Money SDG(mixed $amount, bool $convert = false)
 * @method static Money SEK(mixed $amount, bool $convert = false)
 * @method static Money SGD(mixed $amount, bool $convert = false)
 * @method static Money SHP(mixed $amount, bool $convert = false)
 * @method static Money SLL(mixed $amount, bool $convert = false)
 * @method static Money SOS(mixed $amount, bool $convert = false)
 * @method static Money SRD(mixed $amount, bool $convert = false)
 * @method static Money SSP(mixed $amount, bool $convert = false)
 * @method static Money STD(mixed $amount, bool $convert = false)
 * @method static Money SVC(mixed $amount, bool $convert = false)
 * @method static Money SYP(mixed $amount, bool $convert = false)
 * @method static Money SZL(mixed $amount, bool $convert = false)
 * @method static Money THB(mixed $amount, bool $convert = false)
 * @method static Money TJS(mixed $amount, bool $convert = false)
 * @method static Money TMT(mixed $amount, bool $convert = false)
 * @method static Money TND(mixed $amount, bool $convert = false)
 * @method static Money TOP(mixed $amount, bool $convert = false)
 * @method static Money TRY(mixed $amount, bool $convert = false)
 * @method static Money TTD(mixed $amount, bool $convert = false)
 * @method static Money TWD(mixed $amount, bool $convert = false)
 * @method static Money TZS(mixed $amount, bool $convert = false)
 * @method static Money UAH(mixed $amount, bool $convert = false)
 * @method static Money UGX(mixed $amount, bool $convert = false)
 * @method static Money USD(mixed $amount, bool $convert = false)
 * @method static Money UYU(mixed $amount, bool $convert = false)
 * @method static Money UZS(mixed $amount, bool $convert = false)
 * @method static Money VEF(mixed $amount, bool $convert = false)
 * @method static Money VND(mixed $amount, bool $convert = false)
 * @method static Money VUV(mixed $amount, bool $convert = false)
 * @method static Money WST(mixed $amount, bool $convert = false)
 * @method static Money XAF(mixed $amount, bool $convert = false)
 * @method static Money XAG(mixed $amount, bool $convert = false)
 * @method static Money XAU(mixed $amount, bool $convert = false)
 * @method static Money XCD(mixed $amount, bool $convert = false)
 * @method static Money XDR(mixed $amount, bool $convert = false)
 * @method static Money XOF(mixed $amount, bool $convert = false)
 * @method static Money XPF(mixed $amount, bool $convert = false)
 * @method static Money YER(mixed $amount, bool $convert = false)
 * @method static Money ZAR(mixed $amount, bool $convert = false)
 * @method static Money ZMW(mixed $amount, bool $convert = false)
 * @method static Money ZWL(mixed $amount, bool $convert = false)
 */
class Money implements Arrayable, Jsonable, JsonSerializable, Renderable
{
    const ROUND_HALF_UP = PHP_ROUND_HALF_UP;
    const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;
    const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;
    const ROUND_HALF_ODD = PHP_ROUND_HALF_ODD;

    /**
     * @var int|float
     */
    protected $amount;

    /**
     * @var \Akaunting\Money\Currency
     */
    protected $currency;

    /**
     * @var bool
     */
    protected $mutable = false;

    /**
     * @var string
     */
    protected static $locale;

    /**
     * Create a new instance.
     *
     * @param mixed                     $amount
     * @param \Akaunting\Money\Currency $currency
     * @param bool                      $convert
     *
     * @throws \UnexpectedValueException
     */
    public function __construct($amount, Currency $currency, $convert = false)
    {
        $this->currency = $currency;
        $this->amount = $this->parseAmount($amount, $convert);
    }

    /**
     * parseAmount.
     *
     * @param mixed $amount
     * @param bool  $convert
     *
     * @throws \UnexpectedValueException
     *
     * @return int|float
     */
    protected function parseAmount($amount, $convert = false)
    {
        $amount = $this->parseAmountFromString($this->parseAmountFromCallable($amount));

        if (is_int($amount)) {
            return (int) $this->convertAmount($amount, $convert);
        }

        if (is_float($amount)) {
            return (float) $this->round($this->convertAmount($amount, $convert));
        }

        if ($amount instanceof static) {
            return $this->convertAmount($amount->getAmount(), $convert);
        }

        throw new UnexpectedValueException('Invalid amount "' . $amount . '"');
    }

    /**
     * parseAmountFromCallable.
     *
     * @param mixed $amount
     *
     * @return mixed
     */
    protected function parseAmountFromCallable($amount)
    {
        if (!is_callable($amount)) {
            return $amount;
        }

        return $amount();
    }

    /**
     * parseAmountFromString.
     *
     * @param mixed $amount
     *
     * @return int|float|mixed
     */
    protected function parseAmountFromString($amount)
    {
        if (!is_string($amount)) {
            return $amount;
        }

        $thousandsSeparator = $this->currency->getThousandsSeparator();
        $decimalMark = $this->currency->getDecimalMark();

        $amount = str_replace($this->currency->getSymbol(), '', $amount);
        $amount = preg_replace('/[^0-9\\' . $thousandsSeparator . '\\' . $decimalMark . '\-\+]/', '', $amount);
        $amount = str_replace($this->currency->getThousandsSeparator(), '', $amount);
        $amount = str_replace($this->currency->getDecimalMark(), '.', $amount);

        if (preg_match('/^([\-\+])?\d+$/', $amount)) {
            $amount = (int) $amount;
        } elseif (preg_match('/^([\-\+])?\d+\.\d+$/', $amount)) {
            $amount = (float) $amount;
        }

        return $amount;
    }

    /**
     * convertAmount.
     *
     * @param int|float $amount
     * @param bool      $convert
     *
     * @return int|float
     */
    protected function convertAmount($amount, $convert = false)
    {
        if (!$convert) {
            return $amount;
        }

        return $amount * $this->currency->getSubunit();
    }

    /**
     * __callStatic.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return \Akaunting\Money\Money
     */
    public static function __callStatic($method, array $arguments)
    {
        $convert = (isset($arguments[1]) && is_bool($arguments[1])) ? (bool) $arguments[1] : false;

        return new static($arguments[0], new Currency($method), $convert);
    }

    /**
     * getLocale.
     *
     * @return string
     */
    public static function getLocale()
    {
        if (!isset(static::$locale)) {
            static::$locale = 'en_GB';
        }

        return static::$locale;
    }

    /**
     * setLocale.
     *
     * @param string $locale
     *
     * @return void
     */
    public static function setLocale($locale)
    {
        static::$locale = $locale;
    }

    /**
     * assertSameCurrency.
     *
     * @param \Akaunting\Money\Money $other
     *
     * @throws \InvalidArgumentException
     */
    protected function assertSameCurrency(self $other)
    {
        if (!$this->isSameCurrency($other)) {
            throw new InvalidArgumentException('Different currencies "' . $this->currency . '" and "' . $other->currency . '"');
        }
    }

    /**
     * assertOperand.
     *
     * @param int|float $operand
     *
     * @throws \InvalidArgumentException
     */
    protected function assertOperand($operand)
    {
        if (!is_int($operand) && !is_float($operand)) {
            throw new InvalidArgumentException('Operand "' . $operand . '" should be an integer or a float');
        }
    }

    /**
     * assertRoundingMode.
     *
     * @param int $mode
     *
     * @throws \OutOfBoundsException
     */
    protected function assertRoundingMode($mode)
    {
        $modes = [self::ROUND_HALF_DOWN, self::ROUND_HALF_EVEN, self::ROUND_HALF_ODD, self::ROUND_HALF_UP];

        if (!in_array($mode, $modes)) {
            throw new OutOfBoundsException('Rounding mode should be ' . implode(' | ', $modes));
        }
    }

    /**
     * assertDivisor.
     *
     * @param int|float $divisor
     *
     * @throws \InvalidArgumentException
     */
    protected function assertDivisor($divisor)
    {
        if ($divisor == 0) {
            throw new InvalidArgumentException('Division by zero');
        }
    }

    /**
     * getAmount.
     *
     * @param bool $rounded
     *
     * @return int|float
     */
    public function getAmount($rounded = false)
    {
        return $rounded ? $this-> getRoundedAmount() : $this->amount;
    }

    /**
     * getRoundedAmount.
     *
     * @return int|float
     */
    public function getRoundedAmount()
    {
        return $this->round($this->amount);
    }

    /**
     * getValue.
     *
     * @return float
     */
    public function getValue()
    {
        return $this->round($this->amount / $this->currency->getSubunit());
    }

    /**
     * getCurrency.
     *
     * @return \Akaunting\Money\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * isSameCurrency.
     *
     * @param \Akaunting\Money\Money $other
     *
     * @return bool
     */
    public function isSameCurrency(self $other)
    {
        return $this->currency->equals($other->currency);
    }

    /**
     * compare.
     *
     * @param \Akaunting\Money\Money $other
     *
     * @throws \InvalidArgumentException
     *
     * @return int
     */
    public function compare(self $other)
    {
        $this->assertSameCurrency($other);

        if ($this->amount < $other->amount) {
            return -1;
        }

        if ($this->amount > $other->amount) {
            return 1;
        }

        return 0;
    }

    /**
     * equals.
     *
     * @param \Akaunting\Money\Money $other
     *
     * @return bool
     */
    public function equals(self $other)
    {
        return $this->compare($other) == 0;
    }

    /**
     * greaterThan.
     *
     * @param \Akaunting\Money\Money $other
     *
     * @return bool
     */
    public function greaterThan(self $other)
    {
        return $this->compare($other) == 1;
    }

    /**
     * greaterThanOrEqual.
     *
     * @param \Akaunting\Money\Money $other
     *
     * @return bool
     */
    public function greaterThanOrEqual(self $other)
    {
        return $this->compare($other) >= 0;
    }

    /**
     * lessThan.
     *
     * @param \Akaunting\Money\Money $other
     *
     * @return bool
     */
    public function lessThan(self $other)
    {
        return $this->compare($other) == -1;
    }

    /**
     * lessThanOrEqual.
     *
     * @param \Akaunting\Money\Money $other
     *
     * @return bool
     */
    public function lessThanOrEqual(self $other)
    {
        return $this->compare($other) <= 0;
    }

    /**
     * convert.
     *
     * @param \Akaunting\Money\Currency $currency
     * @param int|float                 $ratio
     * @param int                       $rounding_mode
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     *
     * @return \Akaunting\Money\Money
     */
    public function convert(Currency $currency, $ratio, $rounding_mode = self::ROUND_HALF_UP)
    {
        $this->currency = $currency;

        return $this->multiply($ratio, $rounding_mode);
    }

    /**
     * add.
     *
     * @param $addend
     * @param int $rounding_mode
     *
     * @throws \InvalidArgumentException
     *
     * @return \Akaunting\Money\Money
     */
    public function add($addend, $rounding_mode = self::ROUND_HALF_UP)
    {
        if ($addend instanceof static) {
            $this->assertSameCurrency($addend);

            $addend = $addend->getAmount();
        }

        $amount = $this->round($this->amount + $addend, $rounding_mode);

        if ($this->isImmutable()) {
            return new static($amount, $this->currency);
        }

        $this->amount = $amount;

        return $this;
    }

    /**
     * subtract.
     *
     * @param $subtrahend
     * @param int $rounding_mode
     *
     * @throws \InvalidArgumentException
     *
     * @return \Akaunting\Money\Money
     */
    public function subtract($subtrahend, $rounding_mode = self::ROUND_HALF_UP)
    {
        if ($subtrahend instanceof static) {
            $this->assertSameCurrency($subtrahend);

            $subtrahend = $subtrahend->getAmount();
        }

        $amount = $this->round($this->amount - $subtrahend, $rounding_mode);

        if ($this->isImmutable()) {
            return new static($amount, $this->currency);
        }

        $this->amount = $amount;

        return $this;
    }

    /**
     * multiply.
     *
     * @param int|float $multiplier
     * @param int       $rounding_mode
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     *
     * @return \Akaunting\Money\Money
     */
    public function multiply($multiplier, $rounding_mode = self::ROUND_HALF_UP)
    {
        $this->assertOperand($multiplier);

        $amount = $this->round($this->amount * $multiplier, $rounding_mode);

        if ($this->isImmutable()) {
            return new static($amount, $this->currency);
        }

        $this->amount = $amount;

        return $this;
    }

    /**
     * divide.
     *
     * @param int|float $divisor
     * @param int       $rounding_mode
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     *
     * @return \Akaunting\Money\Money
     */
    public function divide($divisor, $rounding_mode = self::ROUND_HALF_UP)
    {
        $this->assertOperand($divisor);
        $this->assertDivisor($divisor);

        $amount = $this->round($this->amount / $divisor, $rounding_mode);

        if ($this->isImmutable()) {
            return new static($amount, $this->currency);
        }

        $this->amount = $amount;

        return $this;
    }

    /**
     * round.
     *
     * @param int|float $amount
     * @param int       $mode
     *
     * @return mixed
     */
    public function round($amount, $mode = self::ROUND_HALF_UP)
    {
        $this->assertRoundingMode($mode);

        return round($amount, $this->currency->getPrecision(), $mode);
    }

    /**
     * allocate.
     *
     * @param array $ratios
     *
     * @return array
     */
    public function allocate(array $ratios)
    {
        $remainder = $this->amount;
        $results = [];
        $total = array_sum($ratios);

        foreach ($ratios as $ratio) {
            $share = floor($this->amount * $ratio / $total);
            $results[] = new static($share, $this->currency);
            $remainder -= $share;
        }

        for ($i = 0; $remainder > 0; $i++) {
            $results[$i]->amount++;
            $remainder--;
        }

        return $results;
    }

    /**
     * isZero.
     *
     * @return bool
     */
    public function isZero()
    {
        return $this->amount == 0;
    }

    /**
     * isPositive.
     *
     * @return bool
     */
    public function isPositive()
    {
        return $this->amount > 0;
    }

    /**
     * isNegative.
     *
     * @return bool
     */
    public function isNegative()
    {
        return $this->amount < 0;
    }

    /**
     * formatLocale.
     *
     * @param string  $locale
     * @param Closure $callback
     *
     * @throws \BadFunctionCallException
     *
     * @return string
     */
    public function formatLocale($locale = null, Closure $callback = null)
    {
        if (!class_exists('\NumberFormatter')) {
            throw new BadFunctionCallException('Class NumberFormatter not exists. Require ext-intl extension.');
        }

        $formatter = new \NumberFormatter($locale ?: static::getLocale(), \NumberFormatter::CURRENCY);

        if (is_callable($callback)) {
            $callback($formatter);
        }

        return $formatter->formatCurrency($this->getValue(), $this->currency->getCurrency());
    }

    /**
     * formatSimple.
     *
     * @return string
     */
    public function formatSimple()
    {
        return number_format(
            $this->getValue(),
            $this->currency->getPrecision(),
            $this->currency->getDecimalMark(),
            $this->currency->getThousandsSeparator()
        );
    }

    /**
     * format.
     *
     * @return string
     */
    public function format()
    {
        $negative = $this->isNegative();
        $value = $this->getValue();
        $amount = $negative ? -$value : $value;
        $thousands = $this->currency->getThousandsSeparator();
        $decimals = $this->currency->getDecimalMark();
        $prefix = $this->currency->getPrefix();
        $suffix = $this->currency->getSuffix();
        $value = number_format($amount, $this->currency->getPrecision(), $decimals, $thousands);

        return ($negative ? '-' : '') . $prefix . $value . $suffix;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'amount'   => $this->amount,
            'value'    => $this->getValue(),
            'currency' => $this->currency,
        ];
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
        return $this->format();
    }

    public function immutable()
    {
        $this->mutable = false;

        return new static($this->amount, $this->currency);
    }

    public function mutable()
    {
        $this->mutable = true;

        return $this;
    }

    public function isMutable()
    {
        return $this->mutable === true;
    }

    public function isImmutable()
    {
        return !$this->isMutable();
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
