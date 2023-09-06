<?php

namespace Bugsnag\Internal;

use Bugsnag\FeatureFlag;

/**
 * @internal
 */
final class FeatureFlagDelegate
{
    /**
     * @var FeatureFlag[]
     * @phpstan-var list<FeatureFlag>
     */
    private $storage = [];

    /**
     * @param string $name
     * @param string|null $variant
     *
     * @return void
     */
    public function add($name, $variant)
    {
        // ensure we're not about to add a duplicate flag
        $this->remove($name);

        $this->storage[] = new FeatureFlag($name, $variant);
    }

    /**
     * @param FeatureFlag[] $featureFlags
     * @phpstan-param list<FeatureFlag> $featureFlags
     *
     * @return void
     */
    public function merge(array $featureFlags)
    {
        foreach ($featureFlags as $flag) {
            if ($flag instanceof FeatureFlag) {
                $this->remove($flag->getName());

                $this->storage[] = $flag;
            }
        }
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function remove($name)
    {
        foreach ($this->storage as $index => $flag) {
            if ($flag->getName() === $name) {
                unset($this->storage[$index]);

                // reindex the array to prevent holes
                $this->storage = array_values($this->storage);

                break;
            }
        }
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->storage = [];
    }

    /**
     * Get the list of stored feature flags as an array.
     *
     * @return \Bugsnag\FeatureFlag[]
     */
    public function toArray()
    {
        return $this->storage;
    }
}
