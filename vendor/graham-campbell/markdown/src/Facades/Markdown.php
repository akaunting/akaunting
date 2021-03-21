<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Markdown.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Markdown\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * This is the markdown facade class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class Markdown extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'markdown';
    }
}
