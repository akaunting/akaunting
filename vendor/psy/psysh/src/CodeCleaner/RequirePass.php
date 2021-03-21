<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2020 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\CodeCleaner;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified as FullyQualifiedName;
use PhpParser\Node\Scalar\LNumber;
use Psy\Exception\ErrorException;
use Psy\Exception\FatalErrorException;

/**
 * Add runtime validation for `require` and `require_once` calls.
 */
class RequirePass extends CodeCleanerPass
{
    private static $requireTypes = [Include_::TYPE_REQUIRE, Include_::TYPE_REQUIRE_ONCE];

    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $origNode)
    {
        if (!$this->isRequireNode($origNode)) {
            return;
        }

        $node = clone $origNode;

        /*
         * rewrite
         *
         *   $foo = require $bar
         *
         * to
         *
         *   $foo = require \Psy\CodeCleaner\RequirePass::resolve($bar)
         */
        $node->expr = new StaticCall(
            new FullyQualifiedName(self::class),
            'resolve',
            [new Arg($origNode->expr), new Arg(new LNumber($origNode->getLine()))],
            $origNode->getAttributes()
        );

        return $node;
    }

    /**
     * Runtime validation that $file can be resolved as an include path.
     *
     * If $file can be resolved, return $file. Otherwise throw a fatal error exception.
     *
     * If $file collides with a path in the currently running PsySH phar, it will be resolved
     * relative to the include path, to prevent PHP from grabbing the phar version of the file.
     *
     * @throws FatalErrorException when unable to resolve include path for $file
     * @throws ErrorException      if $file is empty and E_WARNING is included in error_reporting level
     *
     * @param string $file
     * @param int    $lineNumber Line number of the original require expression
     *
     * @return string Exactly the same as $file, unless $file collides with a path in the currently running phar
     */
    public static function resolve($file, $lineNumber = null)
    {
        $file = (string) $file;

        if ($file === '') {
            // @todo Shell::handleError would be better here, because we could
            // fake the file and line number, but we can't call it statically.
            // So we're duplicating some of the logics here.
            if (\E_WARNING & \error_reporting()) {
                ErrorException::throwException(\E_WARNING, 'Filename cannot be empty', null, $lineNumber);
            }
            // @todo trigger an error as fallback? this is pretty ugly…
            // trigger_error('Filename cannot be empty', E_USER_WARNING);
        }

        $resolvedPath = \stream_resolve_include_path($file);
        if ($file === '' || !$resolvedPath) {
            $msg = \sprintf("Failed opening required '%s'", $file);
            throw new FatalErrorException($msg, 0, \E_ERROR, null, $lineNumber);
        }

        // Special case: if the path is not already relative or absolute, and it would resolve to
        // something inside the currently running phar (e.g. `vendor/autoload.php`), we'll resolve
        // it relative to the include path so PHP won't grab the phar version.
        //
        // Note that this only works if the phar has `psysh` in the path. We might want to lift this
        // restriction and special case paths that would collide with any running phar?
        if ($resolvedPath !== $file && $file[0] !== '.') {
            $runningPhar = \Phar::running();
            if (\strpos($runningPhar, 'psysh') !== false && \is_file($runningPhar.\DIRECTORY_SEPARATOR.$file)) {
                foreach (self::getIncludePath() as $prefix) {
                    $resolvedPath = $prefix.\DIRECTORY_SEPARATOR.$file;
                    if (\is_file($resolvedPath)) {
                        return $resolvedPath;
                    }
                }
            }
        }

        return $file;
    }

    private function isRequireNode(Node $node)
    {
        return $node instanceof Include_ && \in_array($node->type, self::$requireTypes);
    }

    private static function getIncludePath()
    {
        if (\PATH_SEPARATOR === ':') {
            return \preg_split('#:(?!//)#', \get_include_path());
        }

        return \explode(\PATH_SEPARATOR, \get_include_path());
    }
}
