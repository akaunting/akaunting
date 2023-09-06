<?php

namespace Bugsnag\Internal;

use GuzzleHttp;

/**
 * @internal
 */
final class GuzzleCompat
{
    /**
     * @return bool
     */
    public static function isUsingGuzzle5()
    {
        if (defined(GuzzleHttp\ClientInterface::class.'::VERSION')) {
            $version = constant(GuzzleHttp\ClientInterface::class.'::VERSION');

            return version_compare($version, '5.0.0', '>=')
                && version_compare($version, '6.0.0', '<');
        }

        return false;
    }

    /**
     * Get the base URL/URI option name, which depends on the Guzzle version.
     *
     * @return string
     */
    public static function getBaseUriOptionName()
    {
        return self::isUsingGuzzle5() ? 'base_url' : 'base_uri';
    }

    /**
     * Get the base URL/URI, which depends on the Guzzle version.
     *
     * @param GuzzleHttp\ClientInterface $guzzle
     *
     * @return mixed
     */
    public static function getBaseUri(GuzzleHttp\ClientInterface $guzzle)
    {
        // TODO: validate this by running PHPStan with Guzzle 5
        return self::isUsingGuzzle5()
            ? $guzzle->getBaseUrl() // @phpstan-ignore-line
            : $guzzle->getConfig(self::getBaseUriOptionName());
    }

    /**
     * Apply the given $requestOptions to the Guzzle $options array, if they are
     * not already set.
     *
     * The layout of request options differs in Guzzle 5 to 6/7; in Guzzle 5
     * request options live in a 'defaults' array, but in 6/7 they are in the
     * top level
     *
     * @param array $options
     * @param array $requestOptions
     *
     * @return array
     */
    public static function applyRequestOptions(array $options, array $requestOptions)
    {
        if (self::isUsingGuzzle5()) {
            if (!isset($options['defaults'])) {
                $options['defaults'] = [];
            }

            foreach ($requestOptions as $key => $value) {
                if (!isset($options['defaults'][$key])) {
                    $options['defaults'][$key] = $value;
                }
            }

            return $options;
        }

        foreach ($requestOptions as $key => $value) {
            if (!isset($options[$key])) {
                $options[$key] = $value;
            }
        }

        return $options;
    }
}
