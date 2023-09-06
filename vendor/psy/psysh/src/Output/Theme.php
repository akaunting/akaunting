<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2023 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Output;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * An output Theme, which controls prompt strings, formatter styles, and compact output.
 */
class Theme
{
    const MODERN_THEME = []; // Defaults :)

    const COMPACT_THEME = [
        'compact' => true,
    ];

    const CLASSIC_THEME = [
        'compact' => true,

        'prompt'       => '>>> ',
        'bufferPrompt' => '... ',
        'replayPrompt' => '--> ',
        'returnValue'  => '=>  ',
    ];

    const DEFAULT_STYLES = [
        'info'    => ['white', 'blue', ['bold']],
        'warning' => ['black', 'yellow'],
        'error'   => ['white', 'red', ['bold']],
        'whisper' => ['gray'],

        'aside'  => ['blue'],
        'strong' => [null, null, ['bold']],
        'return' => ['cyan'],
        'urgent' => ['red'],
        'hidden' => ['black'],

        // Visibility
        'public'    => [null, null, ['bold']],
        'protected' => ['yellow'],
        'private'   => ['red'],
        'global'    => ['cyan', null, ['bold']],
        'const'     => ['cyan'],
        'class'     => ['blue', null, ['underscore']],
        'function'  => [null],
        'default'   => [null],

        // Types
        'number'       => ['magenta'],
        'integer'      => ['magenta'],
        'float'        => ['yellow'],
        'string'       => ['green'],
        'bool'         => ['cyan'],
        'keyword'      => ['yellow'],
        'comment'      => ['blue'],
        'code_comment' => ['gray'],
        'object'       => ['blue'],
        'resource'     => ['yellow'],

        // Code-specific formatting
        'inline_html' => ['cyan'],
    ];

    const ERROR_STYLES = ['info', 'warning', 'error', 'whisper', 'class'];

    private $compact = false;

    private $prompt = '> ';
    private $bufferPrompt = '. ';
    private $replayPrompt = '- ';
    private $returnValue = '= ';

    private $grayFallback = 'blue';

    private $styles = [];

    /**
     * @param string|array $config theme name or config options
     */
    public function __construct($config = 'modern')
    {
        if (\is_string($config)) {
            switch ($config) {
                case 'modern':
                    $config = static::MODERN_THEME;
                    break;

                case 'compact':
                    $config = static::COMPACT_THEME;
                    break;

                case 'classic':
                    $config = static::CLASSIC_THEME;
                    break;

                default:
                    \trigger_error(\sprintf('Unknown theme: %s', $config), \E_USER_NOTICE);
                    $config = static::MODERN_THEME;
                    break;
            }
        }

        if (!\is_array($config)) {
            throw new \InvalidArgumentException('Invalid theme config');
        }

        foreach ($config as $name => $value) {
            switch ($name) {
                case 'compact':
                    $this->setCompact($value);
                    break;

                case 'prompt':
                    $this->setPrompt($value);
                    break;

                case 'bufferPrompt':
                    $this->setBufferPrompt($value);
                    break;

                case 'replayPrompt':
                    $this->setReplayPrompt($value);
                    break;

                case 'returnValue':
                    $this->setReturnValue($value);
                    break;

                case 'grayFallback':
                    $this->setGrayFallback($value);
                    break;
            }
        }

        $this->setStyles($config['styles'] ?? []);
    }

    /**
     * Enable or disable compact output.
     */
    public function setCompact(bool $compact)
    {
        $this->compact = $compact;
    }

    /**
     * Get whether to use compact output.
     */
    public function compact(): bool
    {
        return $this->compact;
    }

    /**
     * Set the prompt string.
     */
    public function setPrompt(string $prompt)
    {
        $this->prompt = $prompt;
    }

    /**
     * Get the prompt string.
     */
    public function prompt(): string
    {
        return $this->prompt;
    }

    /**
     * Set the buffer prompt string (used for multi-line input continuation).
     */
    public function setBufferPrompt(string $bufferPrompt)
    {
        $this->bufferPrompt = $bufferPrompt;
    }

    /**
     * Get the buffer prompt string (used for multi-line input continuation).
     */
    public function bufferPrompt(): string
    {
        return $this->bufferPrompt;
    }

    /**
     * Set the prompt string used when replaying history.
     */
    public function setReplayPrompt(string $replayPrompt)
    {
        $this->replayPrompt = $replayPrompt;
    }

    /**
     * Get the prompt string used when replaying history.
     */
    public function replayPrompt(): string
    {
        return $this->replayPrompt;
    }

    /**
     * Set the return value marker.
     */
    public function setReturnValue(string $returnValue)
    {
        $this->returnValue = $returnValue;
    }

    /**
     * Get the return value marker.
     */
    public function returnValue(): string
    {
        return $this->returnValue;
    }

    /**
     * Set the fallback color when "gray" is unavailable.
     */
    public function setGrayFallback(string $grayFallback)
    {
        $this->grayFallback = $grayFallback;
    }

    /**
     * Set the shell output formatter styles.
     *
     * Accepts a map from style name to [fg, bg, options], for example:
     *
     *     [
     *         'error' => ['white', 'red', ['bold']],
     *         'warning' => ['black', 'yellow'],
     *     ]
     *
     * Foreground, background or options can be null, or even omitted entirely.
     */
    public function setStyles(array $styles)
    {
        foreach (\array_keys(static::DEFAULT_STYLES) as $name) {
            $this->styles[$name] = $styles[$name] ?? static::DEFAULT_STYLES[$name];
        }
    }

    /**
     * Apply the current output formatter styles.
     */
    public function applyStyles(OutputFormatterInterface $formatter, bool $useGrayFallback)
    {
        foreach (\array_keys(static::DEFAULT_STYLES) as $name) {
            $formatter->setStyle($name, new OutputFormatterStyle(...$this->getStyle($name, $useGrayFallback)));
        }
    }

    /**
     * Apply the current output formatter error styles.
     */
    public function applyErrorStyles(OutputFormatterInterface $errorFormatter, bool $useGrayFallback)
    {
        foreach (static::ERROR_STYLES as $name) {
            $errorFormatter->setStyle($name, new OutputFormatterStyle(...$this->getStyle($name, $useGrayFallback)));
        }
    }

    private function getStyle(string $name, bool $useGrayFallback): array
    {
        return \array_map(function ($style) use ($useGrayFallback) {
            return ($useGrayFallback && $style === 'gray') ? $this->grayFallback : $style;
        }, $this->styles[$name]);
    }
}
