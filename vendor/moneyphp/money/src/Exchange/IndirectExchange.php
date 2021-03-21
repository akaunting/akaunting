<?php

namespace Money\Exchange;

use Money\Calculator;
use Money\Calculator\BcMathCalculator;
use Money\Calculator\GmpCalculator;
use Money\Calculator\PhpCalculator;
use Money\Currencies;
use Money\Currency;
use Money\CurrencyPair;
use Money\Exception\UnresolvableCurrencyPairException;
use Money\Exchange;

/**
 * Provides a way to get an exchange rate through a minimal set of intermediate conversions.
 *
 * @author Michael Cordingley <Michael.Cordingley@gmail.com>
 */
final class IndirectExchange implements Exchange
{
    /**
     * @var Calculator
     */
    private static $calculator;

    /**
     * @var array
     */
    private static $calculators = [
        BcMathCalculator::class,
        GmpCalculator::class,
        PhpCalculator::class,
    ];

    /**
     * @var Currencies
     */
    private $currencies;

    /**
     * @var Exchange
     */
    private $exchange;

    /**
     * @param Exchange   $exchange
     * @param Currencies $currencies
     */
    public function __construct(Exchange $exchange, Currencies $currencies)
    {
        $this->exchange = $exchange;
        $this->currencies = $currencies;
    }

    /**
     * @param string $calculator
     */
    public static function registerCalculator($calculator)
    {
        if (is_a($calculator, Calculator::class, true) === false) {
            throw new \InvalidArgumentException('Calculator must implement '.Calculator::class);
        }

        array_unshift(self::$calculators, $calculator);
    }

    /**
     * {@inheritdoc}
     */
    public function quote(Currency $baseCurrency, Currency $counterCurrency)
    {
        try {
            return $this->exchange->quote($baseCurrency, $counterCurrency);
        } catch (UnresolvableCurrencyPairException $exception) {
            $rate = array_reduce($this->getConversions($baseCurrency, $counterCurrency), function ($carry, CurrencyPair $pair) {
                return static::getCalculator()->multiply($carry, $pair->getConversionRatio());
            }, '1.0');

            return new CurrencyPair($baseCurrency, $counterCurrency, $rate);
        }
    }

    /**
     * @param Currency $baseCurrency
     * @param Currency $counterCurrency
     *
     * @return CurrencyPair[]
     *
     * @throws UnresolvableCurrencyPairException
     */
    private function getConversions(Currency $baseCurrency, Currency $counterCurrency)
    {
        $startNode = $this->initializeNode($baseCurrency);
        $startNode->discovered = true;

        $nodes = [$baseCurrency->getCode() => $startNode];

        $frontier = new \SplQueue();
        $frontier->enqueue($startNode);

        while ($frontier->count()) {
            /** @var \stdClass $currentNode */
            $currentNode = $frontier->dequeue();

            /** @var Currency $currentCurrency */
            $currentCurrency = $currentNode->currency;

            if ($currentCurrency->equals($counterCurrency)) {
                return $this->reconstructConversionChain($nodes, $currentNode);
            }

            /** @var Currency $candidateCurrency */
            foreach ($this->currencies as $candidateCurrency) {
                if (!isset($nodes[$candidateCurrency->getCode()])) {
                    $nodes[$candidateCurrency->getCode()] = $this->initializeNode($candidateCurrency);
                }

                /** @var \stdClass $node */
                $node = $nodes[$candidateCurrency->getCode()];

                if (!$node->discovered) {
                    try {
                        // Check if the candidate is a neighbor. This will throw an exception if it isn't.
                        $this->exchange->quote($currentCurrency, $candidateCurrency);

                        $node->discovered = true;
                        $node->parent = $currentNode;

                        $frontier->enqueue($node);
                    } catch (UnresolvableCurrencyPairException $exception) {
                        // Not a neighbor. Move on.
                    }
                }
            }
        }

        throw UnresolvableCurrencyPairException::createFromCurrencies($baseCurrency, $counterCurrency);
    }

    /**
     * @param Currency $currency
     *
     * @return \stdClass
     */
    private function initializeNode(Currency $currency)
    {
        $node = new \stdClass();

        $node->currency = $currency;
        $node->discovered = false;
        $node->parent = null;

        return $node;
    }

    /**
     * @param array     $currencies
     * @param \stdClass $goalNode
     *
     * @return CurrencyPair[]
     */
    private function reconstructConversionChain(array $currencies, \stdClass $goalNode)
    {
        $current = $goalNode;
        $conversions = [];

        while ($current->parent) {
            $previous = $currencies[$current->parent->currency->getCode()];
            $conversions[] = $this->exchange->quote($previous->currency, $current->currency);
            $current = $previous;
        }

        return array_reverse($conversions);
    }

    /**
     * @return Calculator
     */
    private function getCalculator()
    {
        if (null === self::$calculator) {
            self::$calculator = self::initializeCalculator();
        }

        return self::$calculator;
    }

    /**
     * @return Calculator
     *
     * @throws \RuntimeException If cannot find calculator for money calculations
     */
    private static function initializeCalculator()
    {
        $calculators = self::$calculators;

        foreach ($calculators as $calculator) {
            /** @var Calculator $calculator */
            if ($calculator::supported()) {
                return new $calculator();
            }
        }

        throw new \RuntimeException('Cannot find calculator for money calculations');
    }
}
