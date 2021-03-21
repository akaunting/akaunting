<?php

/*
 * KKSzymanowski/Traitor
 * Add a trait use statement to existing class
 *
 * @package KKSzymanowski/Traitor
 * @author Kuba Szymanowski <kuba.szymanowski@inf24.pl>
 * @link https://github.com/kkszymanowski/traitor
 * @license MIT
 */

namespace Traitor;

class Traitor
{
    /**
     * @param string $trait
     *
     * @return TraitUseAdder
     */
    public static function addTrait($trait)
    {
        $instance = new TraitUseAdder();

        return $instance->addTraits([$trait]);
    }

    /**
     * @param array $traits
     *
     * @return TraitUseAdder
     */
    public static function addTraits($traits)
    {
        $instance = new TraitUseAdder();

        return $instance->addTraits($traits);
    }

    /**
     * Check if provided class uses a specific trait.
     *
     * @param string $className
     * @param string $traitName
     * @return bool
     */
    public static function alreadyUses($className, $traitName)
    {
        return in_array($traitName, class_uses($className));
    }
}
