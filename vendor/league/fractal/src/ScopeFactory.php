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

class ScopeFactory implements ScopeFactoryInterface
{
    /**
     * @param Manager $manager
     * @param ResourceInterface $resource
     * @param string|null $scopeIdentifier
     * @return Scope
     */
    public function createScopeFor(Manager $manager, ResourceInterface $resource, $scopeIdentifier = null)
    {
        return new Scope($manager, $resource, $scopeIdentifier);
    }

    /**
     * @param Manager $manager
     * @param Scope $parentScopeInstance
     * @param ResourceInterface $resource
     * @param string|null $scopeIdentifier
     * @return Scope
     */
    public function createChildScopeFor(Manager $manager, Scope $parentScopeInstance, ResourceInterface $resource, $scopeIdentifier = null)
    {
        $scopeInstance = $this->createScopeFor($manager, $resource, $scopeIdentifier);

        // This will be the new children list of parents (parents parents, plus the parent)
        $scopeArray = $parentScopeInstance->getParentScopes();
        $scopeArray[] = $parentScopeInstance->getScopeIdentifier();

        $scopeInstance->setParentScopes($scopeArray);

        return $scopeInstance;
    }
}
