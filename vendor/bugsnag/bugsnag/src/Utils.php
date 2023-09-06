<?php

namespace Bugsnag;

use Normalizer;

class Utils
{
    /**
     * Checks whether the given function name is available.
     *
     * @param string $func the function name
     *
     * @return bool
     */
    public static function functionAvailable($func)
    {
        $disabled = explode(',', ini_get('disable_functions'));

        return function_exists($func) && !in_array($func, $disabled);
    }

    /**
     * Gets the current user's identity for build reporting.
     *
     * @return string
     */
    public static function getBuilderName()
    {
        $builderName = null;
        if (self::functionAvailable('exec')) {
            $output = [];
            $success = 0;
            exec('whoami', $output, $success);
            if ($success == 0) {
                $builderName = $output[0];
            }
        }
        if (is_null($builderName)) {
            $builderName = get_current_user();
        }

        return $builderName;
    }

    /**
     * Check if two strings are equal, ignoring case.
     *
     * @param string $a
     * @param string $b
     *
     * @return bool
     */
    public static function stringCaseEquals($a, $b)
    {
        // Avoid unicode normalisation and MB comparison if possible
        if (strcasecmp($a, $b) === 0) {
            return true;
        }

        // Normalise code points into their decomposed form. For example "ñ"
        // can be a single code point (U+00F1) or "n" (U+006E) with a combining
        // tilde (U+0303). The decomposed form will always represent this as
        // U+006E and U+0303, which means we'll match strings more accurately
        // and makes case-insensitive comparisons easier
        if (function_exists('normalizer_is_normalized')
            && function_exists('normalizer_normalize')
        ) {
            $form = Normalizer::NFD;

            if (!normalizer_is_normalized($a, $form)) {
                $a = normalizer_normalize($a, $form);
            }

            if (!normalizer_is_normalized($b, $form)) {
                $b = normalizer_normalize($b, $form);
            }
        }

        if (function_exists('mb_stripos') && function_exists('mb_strlen')) {
            // There's no MB equivalent to strcasecmp, so we have to use
            // mb_stripos with a length check instead
            return mb_strlen($a) === mb_strlen($b) && mb_stripos($a, $b) === 0;
        }

        // If the MB extension isn't available we can still use strcasecmp
        // This will still work for multi-byte strings in some cases because
        // the strings were normalised
        return strcasecmp($a, $b) === 0;
    }
}
