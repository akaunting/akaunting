<?php

namespace Bugsnag;

interface FeatureDataStore
{
    /**
     * Add a single feature flag.
     *
     * @param string $name
     * @param string|null $variant
     *
     * @return void
     */
    public function addFeatureFlag($name, $variant = null);

    /**
     * Add multiple feature flags.
     *
     * The new flags will be merged with any existing feature flags, with the
     * newer variant values taking precedence
     *
     * @param array $featureFlags
     * @phpstan-param list<FeatureFlag> $featureFlags
     *
     * @return void
     */
    public function addFeatureFlags(array $featureFlags);

    /**
     * Remove a single feature flag by name.
     *
     * @param string $name
     *
     * @return void
     */
    public function clearFeatureFlag($name);

    /**
     * Remove all feature flags.
     *
     * @return void
     */
    public function clearFeatureFlags();
}
