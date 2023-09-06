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

namespace GrahamCampbell\Markdown\View\Directive;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
interface DirectiveInterface
{
    /**
     * Normalize and render the markdown.
     *
     * @param string $markdown
     *
     * @return string
     */
    public function render(string $markdown): string;
}
