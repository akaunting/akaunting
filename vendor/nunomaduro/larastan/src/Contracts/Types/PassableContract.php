<?php
/*
 * This file is part of PhpStorm.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NunoMaduro\Larastan\Contracts\Types;

use PHPStan\Type\Type;

/**
 * @internal
 */
interface PassableContract
{
    /**
     * @return \PHPStan\Type\Type
     */
    public function getType(): Type;

    /**
     * @param \PHPStan\Type\Type $type
     *
     * @return void
     */
    public function setType(Type $type): void;
}
