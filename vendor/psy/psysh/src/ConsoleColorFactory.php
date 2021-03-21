<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2020 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy;

use JakubOnderka\PhpConsoleColor\ConsoleColor;

/**
 * @deprecated Nothing should use this anymore
 */
class ConsoleColorFactory
{
    /**
     * @param string $colorMode
     */
    public function __construct($colorMode)
    {
        // Nothing to see here
    }

    /**
     * Get a `ConsoleColor` instance configured according to the given color
     * mode.
     *
     * @return ConsoleColor
     */
    public function getConsoleColor()
    {
        return new ConsoleColor(); // /shrug
    }
}
