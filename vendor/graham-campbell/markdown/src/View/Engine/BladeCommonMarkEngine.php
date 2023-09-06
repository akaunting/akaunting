<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Markdown.
 *
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Markdown\View\Engine;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\CompilerInterface;
use Illuminate\View\Engines\CompilerEngine;
use League\CommonMark\ConverterInterface;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class BladeCommonMarkEngine extends CompilerEngine
{
    private ConverterInterface $converter;

    /**
     * @param \Illuminate\View\Compilers\CompilerInterface $compiler
     * @param \Illuminate\Filesystem\Filesystem            $files
     * @param \League\CommonMark\ConverterInterface        $converter
     *
     * @return void
     */
    public function __construct(CompilerInterface $compiler, Filesystem $files, ConverterInterface $converter)
    {
        parent::__construct($compiler, $files);

        $this->converter = $converter;
    }

    /**
     * Get the evaluated contents of the view.
     *
     * @param string $path
     * @param array  $data
     *
     * @return string
     */
    public function get($path, array $data = []): string
    {
        $contents = parent::get($path, $data);

        return $this->converter->convert($contents)->getContent();
    }
}
