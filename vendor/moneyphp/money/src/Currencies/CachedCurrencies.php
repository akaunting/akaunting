<?php

declare(strict_types=1);

namespace Money\Currencies;

use ArrayIterator;
use Cache\TagInterop\TaggableCacheItemInterface;
use CallbackFilterIterator;
use Money\Currencies;
use Money\Currency;
use Psr\Cache\CacheItemPoolInterface;
use Traversable;

use function iterator_to_array;

/**
 * Cache the result of currency checking.
 */
final class CachedCurrencies implements Currencies
{
    private Currencies $currencies;

    private CacheItemPoolInterface $pool;

    public function __construct(Currencies $currencies, CacheItemPoolInterface $pool)
    {
        $this->currencies = $currencies;
        $this->pool       = $pool;
    }

    public function contains(Currency $currency): bool
    {
        $item = $this->pool->getItem('currency|availability|' . $currency->getCode());

        if ($item->isHit() === false) {
            $item->set($this->currencies->contains($currency));

            if ($item instanceof TaggableCacheItemInterface) {
                $item->setTags(['currency.availability']);
            }

            $this->pool->save($item);
        }

        return (bool) $item->get();
    }

    public function subunitFor(Currency $currency): int
    {
        $item = $this->pool->getItem('currency|subunit|' . $currency->getCode());

        if ($item->isHit() === false) {
            $item->set($this->currencies->subunitFor($currency));

            if ($item instanceof TaggableCacheItemInterface) {
                $item->setTags(['currency.subunit']);
            }

            $this->pool->save($item);
        }

        return (int) $item->get();
    }

    /** {@inheritDoc} */
    public function getIterator(): Traversable
    {
        return new CallbackFilterIterator(
            new ArrayIterator(iterator_to_array($this->currencies->getIterator())),
            function (Currency $currency): bool {
                $item = $this->pool->getItem('currency|availability|' . $currency->getCode());
                $item->set(true);

                if ($item instanceof TaggableCacheItemInterface) {
                    $item->setTags(['currency.availability']);
                }

                $this->pool->save($item);

                return true;
            }
        );
    }
}
