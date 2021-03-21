<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * Original code based on the CommonMark JS reference parser (https://bitly.com/commonmark-js)
 *  - (c) John MacFarlane
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Reference;

use League\CommonMark\Normalizer\TextNormalizer;

/**
 * A collection of references, indexed by label
 */
final class ReferenceMap implements ReferenceMapInterface
{
    /** @var TextNormalizer */
    private $normalizer;

    /**
     * @var ReferenceInterface[]
     */
    private $references = [];

    public function __construct()
    {
        $this->normalizer = new TextNormalizer();
    }

    public function addReference(ReferenceInterface $reference): void
    {
        $key = $this->normalizer->normalize($reference->getLabel());

        $this->references[$key] = $reference;
    }

    public function contains(string $label): bool
    {
        $label = $this->normalizer->normalize($label);

        return isset($this->references[$label]);
    }

    public function getReference(string $label): ?ReferenceInterface
    {
        $label = $this->normalizer->normalize($label);

        if (!isset($this->references[$label])) {
            return null;
        }

        return $this->references[$label];
    }

    public function listReferences(): iterable
    {
        return \array_values($this->references);
    }
}
