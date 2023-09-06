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
use Illuminate\View\Engines\PhpEngine;
use League\CommonMark\ConverterInterface;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class PhpCommonMarkEngine extends PhpEngine
{
    private ConverterInterface $converter;

    /**
     * @param \Illuminate\Filesystem\Filesystem     $files
     * @param \League\CommonMark\ConverterInterface $converter
     *
     * @return void
     */
    public function __construct(Filesystem $files, ConverterInterface $converter)
    {
        parent::__construct($files);

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
