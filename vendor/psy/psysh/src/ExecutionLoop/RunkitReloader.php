<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2023 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\ExecutionLoop;

use Psy\Exception\ParseErrorException;
use Psy\ParserFactory;
use Psy\Shell;

/**
 * A runkit-based code reloader, which is pretty much magic.
 */
class RunkitReloader extends AbstractListener
{
    private $parser;
    private $timestamps = [];

    /**
     * Only enabled if Runkit is installed.
     */
    public static function isSupported(): bool
    {
        // runkit_import was removed in runkit7-4.0.0a1
        return \extension_loaded('runkit') || \extension_loaded('runkit7') && \function_exists('runkit_import');
    }

    /**
     * Construct a Runkit Reloader.
     *
     * @todo Pass in Parser Factory instance for dependency injection?
     */
    public function __construct()
    {
        $parserFactory = new ParserFactory();
        $this->parser = $parserFactory->createParser();
    }

    /**
     * Reload code on input.
     *
     * @param Shell  $shell
     * @param string $input
     */
    public function onInput(Shell $shell, string $input)
    {
        $this->reload($shell);
    }

    /**
     * Look through included files and update anything with a new timestamp.
     *
     * @param Shell $shell
     */
    private function reload(Shell $shell)
    {
        \clearstatcache();
        $modified = [];

        foreach (\get_included_files() as $file) {
            $timestamp = \filemtime($file);

            if (!isset($this->timestamps[$file])) {
                $this->timestamps[$file] = $timestamp;
                continue;
            }

            if ($this->timestamps[$file] === $timestamp) {
                continue;
            }

            if (!$this->lintFile($file)) {
                $msg = \sprintf('Modified file "%s" could not be reloaded', $file);
                $shell->writeException(new ParseErrorException($msg));
                continue;
            }

            $modified[] = $file;
            $this->timestamps[$file] = $timestamp;
        }

        // switch (count($modified)) {
        //     case 0:
        //         return;

        //     case 1:
        //         printf("Reloading modified file: \"%s\"\n", str_replace(getcwd(), '.', $file));
        //         break;

        //     default:
        //         printf("Reloading %d modified files\n", count($modified));
        //         break;
        // }

        foreach ($modified as $file) {
            $flags = (
                RUNKIT_IMPORT_FUNCTIONS |
                RUNKIT_IMPORT_CLASSES |
                RUNKIT_IMPORT_CLASS_METHODS |
                RUNKIT_IMPORT_CLASS_CONSTS |
                RUNKIT_IMPORT_CLASS_PROPS |
                RUNKIT_IMPORT_OVERRIDE
            );

            // these two const cannot be used with RUNKIT_IMPORT_OVERRIDE  in runkit7
            if (\extension_loaded('runkit7')) {
                $flags &= ~RUNKIT_IMPORT_CLASS_PROPS & ~RUNKIT_IMPORT_CLASS_STATIC_PROPS;
                runkit7_import($file, $flags);
            } else {
                runkit_import($file, $flags);
            }
        }
    }

    /**
     * Should this file be re-imported?
     *
     * Use PHP-Parser to ensure that the file is valid PHP.
     *
     * @param string $file
     */
    private function lintFile(string $file): bool
    {
        // first try to parse it
        try {
            $this->parser->parse(\file_get_contents($file));
        } catch (\Throwable $e) {
            return false;
        }

        return true;
    }
}
