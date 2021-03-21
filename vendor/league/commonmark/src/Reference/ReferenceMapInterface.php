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

/**
 * A collection of references
 */
interface ReferenceMapInterface
{
    /**
     * @param ReferenceInterface $reference
     *
     * @return void
     */
    public function addReference(ReferenceInterface $reference): void;

    /**
     * @param string $label
     *
     * @return bool
     */
    public function contains(string $label): bool;

    /**
     * @param string $label
     *
     * @return ReferenceInterface|null
     */
    public function getReference(string $label): ?ReferenceInterface;

    /**
     * Lists all registered references.
     *
     * @return ReferenceInterface[]
     */
    public function listReferences(): iterable;
}
