<?php

declare(strict_types=1);

namespace Money\Exchange;

use Money\Currency;

/** @internal for sole consumption by {@see IndirectExchange} */
final class IndirectExchangeQueuedItem
{
    public Currency $currency;
    public bool $discovered = false;
    public ?self $parent    = null;

    public function __construct(Currency $currency)
    {
        $this->currency = $currency;
    }
}
