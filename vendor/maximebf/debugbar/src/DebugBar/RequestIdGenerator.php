<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebugBar;

/**
 * Basic request ID generator
 */
class RequestIdGenerator implements RequestIdGeneratorInterface
{
    protected $index = 0;

    /**
     * @return string
     */
    public function generate()
    {
        if (function_exists('random_bytes')) {
            // PHP 7 only
            return 'X' . bin2hex(random_bytes(16));
        } else if (function_exists('openssl_random_pseudo_bytes')) {
            // PHP >= 5.3.0, but OpenSSL may not always be available
            return 'X' . bin2hex(openssl_random_pseudo_bytes(16));
        } else {
            // Fall back to a rudimentary ID generator:
            //  * $_SERVER array will make the ID unique to this request.
            //  * spl_object_hash($this) will make the ID unique to this object instance.
            //    (note that object hashes can be reused, but the other data here should prevent issues here).
            //  * uniqid('', true) will use the current microtime(), plus additional random data.
            //  * $this->index guarantees the uniqueness of IDs from the current object.
            $this->index++;
            $entropy = serialize($_SERVER) . uniqid('', true) . spl_object_hash($this) . $this->index;
            return 'X' . md5($entropy);
        }
    }
}
