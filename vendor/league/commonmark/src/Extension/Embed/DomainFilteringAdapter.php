<?php

declare(strict_types=1);

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Extension\Embed;

class DomainFilteringAdapter implements EmbedAdapterInterface
{
    private EmbedAdapterInterface $decorated;

    /** @psalm-var non-empty-string */
    private string $regex;

    /**
     * @param string[] $allowedDomains
     */
    public function __construct(EmbedAdapterInterface $decorated, array $allowedDomains)
    {
        $this->decorated = $decorated;
        $this->regex     = self::createRegex($allowedDomains);
    }

    /**
     * {@inheritDoc}
     */
    public function updateEmbeds(array $embeds): void
    {
        $this->decorated->updateEmbeds(\array_values(\array_filter($embeds, function (Embed $embed): bool {
            return \preg_match($this->regex, $embed->getUrl()) === 1;
        })));
    }

    /**
     * @param string[] $allowedDomains
     *
     * @psalm-return non-empty-string
     */
    private static function createRegex(array $allowedDomains): string
    {
        $allowedDomains = \array_map('preg_quote', $allowedDomains);

        return '/^(?:https?:\/\/)?(?:[^.]+\.)*(' . \implode('|', $allowedDomains) . ')/';
    }
}
