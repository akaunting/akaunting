<?php

/*
 * This file is part of the League\Fractal package.
 *
 * (c) Phil Sturgeon <me@philsturgeon.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Fractal;

use League\Fractal\Resource\ResourceInterface;

/**
 * ScopeFactoryInterface
 *
 * Creates Scope Instances for resources
 */
interface ScopeFactoryInterface
{
    /**
     * @param Manager $manager
     * @param ResourceInterface $resource
     * @param string|null $scopeIdentifier
     * @return Scope
     */
    public function createScopeFor(Manager $manager, ResourceInterface $resource, $scopeIdentifier = null);

    /**
     * @param Manager $manager
     * @param Scope $parentScope
     * @param ResourceInterface $resource
     * @param string|null $scopeIdentifier
     * @return Scope
     */
    public function createChildScopeFor(Manager $manager, Scope $parentScope, ResourceInterface $resource, $scopeIdentifier = null);
}
