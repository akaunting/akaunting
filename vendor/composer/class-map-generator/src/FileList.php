<?php declare(strict_types=1);

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer\ClassMapGenerator;

/**
 * Contains a list of files which were scanned to generate a classmap
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class FileList
{
    /**
     * @var array<non-empty-string, true>
     */
    public $files = [];

    /**
     * @param non-empty-string $path
     */
    public function add(string $path): void
    {
        $this->files[$path] = true;
    }

    /**
     * @param non-empty-string $path
     */
    public function contains(string $path): bool
    {
        return isset($this->files[$path]);
    }
}
