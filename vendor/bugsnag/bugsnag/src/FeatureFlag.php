<?php

namespace Bugsnag;

final class FeatureFlag
{
    /**
     * A name that identifies this feature flag.
     *
     * @var string
     */
    private $name;

    /**
     * An optional variant for this feature flag.
     *
     * @var string|null
     */
    private $variant;

    /**
     * @param string $name a name that identifies this feature flag
     * @param string|null $variant an optional variant for this feature flag.
     */
    public function __construct($name, $variant = null)
    {
        $this->name = $name;

        // ensure the variant can only be null or a string as the API only
        // accepts strings (null values will be omitted from the payload)
        if ($variant !== null && !is_string($variant)) {
            $json = json_encode($variant);

            // if JSON encoding fails, omit the variant
            $variant = $json === false ? null : $json;
        }

        $this->variant = $variant;
    }

    /**
     * Get the feature flag's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the feature flag's variant.
     *
     * @return string|null
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * Convert this feature flag into the format used by the Bugsnag Event API.
     *
     * This has two forms, either with a variant:
     *   { "featureFlag": "name", "variant": "variant" }
     *
     * or if the feature flag has no variant:
     *   { "featureFlag": "no variant" }
     *
     * @return array[]
     * @phpstan-return array{featureFlag: string, variant?: string}
     */
    public function toArray()
    {
        if (is_string($this->variant)) {
            return ['featureFlag' => $this->name, 'variant' => $this->variant];
        }

        return ['featureFlag' => $this->name];
    }
}
