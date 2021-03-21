<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2017, Hoa community. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

if (false === defined('HOA')) {
    define('HOA', true);
}

if (false === defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50400) {
    throw new Exception(
        'Hoa needs at least PHP5.4 to work; you have ' . phpversion() . '.'
    );
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Autoloader.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Consistency.php';

$define = function ($constantName, $constantValue, $case = false) {
    if (!defined($constantName)) {
        return define($constantName, $constantValue, $case);
    }

    return false;
};

$define('SUCCEED',        true);
$define('FAILED',         false);
$define('…',              '__hoa_core_fill');
$define('DS',             DIRECTORY_SEPARATOR);
$define('PS',             PATH_SEPARATOR);
$define('ROOT_SEPARATOR', ';');
$define('RS',             ROOT_SEPARATOR);
$define('CRLF',           "\r\n");
$define('OS_WIN',         defined('PHP_WINDOWS_VERSION_PLATFORM'));
$define('S_64_BITS',      PHP_INT_SIZE == 8);
$define('S_32_BITS',      !S_64_BITS);
$define('PHP_INT_MIN',    ~PHP_INT_MAX);
$define('PHP_FLOAT_MIN',  (float) PHP_INT_MIN);
$define('PHP_FLOAT_MAX',  (float) PHP_INT_MAX);
$define('π',              M_PI);
$define('nil',            null);
$define('_public',        1);
$define('_protected',     2);
$define('_private',       4);
$define('_static',        8);
$define('_abstract',      16);
$define('_pure',          32);
$define('_final',         64);
$define('_dynamic',       ~_static);
$define('_concrete',      ~_abstract);
$define('_overridable',   ~_final);
$define('WITH_COMPOSER',  class_exists('Composer\Autoload\ClassLoader', false) ||
                          ('cli' === PHP_SAPI &&
                          file_exists(__DIR__ . DS . '..' . DS . '..' . DS . 'autoload.php')));

/**
 * Alias of \Hoa\Consistency\Xcallable.
 *
 * @param   mixed   $call    First callable part.
 * @param   mixed   $able    Second callable part (if needed).
 * @return  mixed
 */
if (!function_exists('xcallable')) {
    function xcallable($call, $able = '')
    {
        if ($call instanceof Hoa\Consistency\Xcallable) {
            return $call;
        }

        return new Hoa\Consistency\Xcallable($call, $able);
    }
}
