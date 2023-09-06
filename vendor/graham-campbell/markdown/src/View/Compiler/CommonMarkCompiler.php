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

namespace GrahamCampbell\Markdown\View\Compiler;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\Compiler;
use Illuminate\View\Compilers\CompilerInterface;
use League\CommonMark\ConverterInterface;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class CommonMarkCompiler extends Compiler implements CompilerInterface
{
    private ConverterInterface $converter;

    /**
     * @param \League\CommonMark\ConverterInterface $converter
     * @param \Illuminate\Filesystem\Filesystem     $files
     * @param string                                $cachePath
     *
     * @return void
     */
    public function __construct(ConverterInterface $converter, Filesystem $files, string $cachePath)
    {
        parent::__construct($files, $cachePath);

        $this->converter = $converter;
    }

    /**
     * Compile the view at the given path.
     *
     * @param string $path
     *
     * @return void
     */
    public function compile($path): void
    {
        $content = $this->converter->convert($this->files->get($path))->getContent();

        $this->files->put($this->getCompiledPath($path), $content);
    }
}
