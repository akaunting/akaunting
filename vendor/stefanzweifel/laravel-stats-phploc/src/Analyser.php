<?php declare(strict_types=1);
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\PHPLOC;

use const T_ABSTRACT;
use const T_BOOLEAN_AND;
use const T_BOOLEAN_OR;
use const T_CASE;
use const T_CATCH;
use const T_CLASS;
use const T_COMMENT;
use const T_CONST;
use const T_CONSTANT_ENCAPSED_STRING;
use const T_CURLY_OPEN;
use const T_DECLARE;
use const T_DOC_COMMENT;
use const T_DOLLAR_OPEN_CURLY_BRACES;
use const T_DOUBLE_COLON;
use const T_ELSEIF;
use const T_EXTENDS;
use const T_FINAL;
use const T_FOR;
use const T_FOREACH;
use const T_FUNCTION;
use const T_GLOBAL;
use const T_IF;
use const T_INTERFACE;
use const T_LOGICAL_AND;
use const T_LOGICAL_OR;
use const T_NAMESPACE;
use const T_NEW;
use const T_NS_SEPARATOR;
use const T_OBJECT_OPERATOR;
use const T_PRIVATE;
use const T_PROTECTED;
use const T_PUBLIC;
use const T_STATIC;
use const T_STRING;
use const T_TRAIT;
use const T_USE;
use const T_VARIABLE;
use const T_WHILE;
use const T_WHITESPACE;
use function array_pop;
use function count;
use function file_get_contents;
use function in_array;
use function is_array;
use function is_string;
use function rtrim;
use function str_replace;
use function strpos;
use function strtolower;
use function substr;
use function substr_count;
use function token_get_all;
use function trim;

final class Analyser
{
    /**
     * @var Collector
     */
    private $collector;

    /**
     * @var array
     */
    private $classes = [];

    /**
     * @var array
     */
    private $superGlobals = [
        '$_ENV'             => true,
        '$_POST'            => true,
        '$_GET'             => true,
        '$_COOKIE'          => true,
        '$_SERVER'          => true,
        '$_FILES'           => true,
        '$_REQUEST'         => true,
        '$HTTP_ENV_VARS'    => true,
        '$HTTP_POST_VARS'   => true,
        '$HTTP_GET_VARS'    => true,
        '$HTTP_COOKIE_VARS' => true,
        '$HTTP_SERVER_VARS' => true,
        '$HTTP_POST_FILES'  => true,
    ];

    public function __construct()
    {
        $this->collector = new Collector;
    }

    public function countFiles(array $files, bool $countTests)
    {
        foreach ($files as $file) {
            $this->countFile($file, $countTests);
        }

        return $this->collector->getPublisher()->toArray();
    }

    public function preProcessFile(string $filename): void
    {
        $tokens    = token_get_all(file_get_contents($filename));
        $numTokens = count($tokens);
        $namespace = false;

        for ($i = 0; $i < $numTokens; $i++) {
            if (is_string($tokens[$i])) {
                continue;
            }

            switch ($tokens[$i][0]) {
                case T_NAMESPACE:
                    $namespace = $this->getNamespaceName($tokens, $i);

                    break;

                case T_CLASS:
                    if (!$this->isClassDeclaration($tokens, $i)) {
                        break;
                    }

                    $className = $this->getClassName($namespace, $tokens, $i);

                    if (isset($tokens[$i + 4]) && is_array($tokens[$i + 4]) &&
                        $tokens[$i + 4][0] === T_EXTENDS) {
                        $parent = $this->getClassName($namespace, $tokens, $i + 4);
                    } else {
                        $parent = null;
                    }

                    $this->classes[$className] = $parent;

                    break;
            }
        }
    }

    /**
     * Processes a single file.
     *
     * @param string $filename
     * @param bool   $countTests
     */
    public function countFile($filename, $countTests): void
    {
        if ($countTests) {
            $this->preProcessFile($filename);
        }

        $buffer = file_get_contents($filename);
        $this->collector->incrementLines(substr_count($buffer, "\n"));
        $tokens    = token_get_all($buffer);
        $numTokens = count($tokens);

        unset($buffer);

        $this->collector->addFile($filename);

        $blocks       = [];
        $currentBlock = false;
        $namespace    = false;
        $className    = null;
        $functionName = null;
        $testClass    = false;
        $this->collector->currentClassReset();
        $isLogicalLine = true;
        $isInMethod    = false;

        for ($i = 0; $i < $numTokens; $i++) {
            if (is_string($tokens[$i])) {
                $token = trim($tokens[$i]);

                if ($token === ';') {
                    if ($isLogicalLine) {
                        if ($className !== null && !$testClass) {
                            $this->collector->currentClassIncrementLines();

                            if ($functionName !== null) {
                                $this->collector->currentMethodIncrementLines();
                            }
                        } elseif ($functionName !== null) {
                            $this->collector->incrementFunctionLines();
                        }

                        $this->collector->incrementLogicalLines();
                    }
                    $isLogicalLine = true;
                } elseif ($token === '?' && !$testClass) {
                    if ($className !== null) {
                        $this->collector->currentClassIncrementComplexity();
                        $this->collector->currentMethodIncrementComplexity();
                    }

                    $this->collector->incrementComplexity();
                } elseif ($token === '{') {
                    if ($currentBlock === T_CLASS) {
                        $block = $className;
                    } elseif ($currentBlock === T_FUNCTION) {
                        $block = $functionName;
                    } else {
                        $block = false;
                    }

                    $blocks[] = $block;

                    $currentBlock = false;
                } elseif ($token === '}') {
                    $block = array_pop($blocks);

                    if ($block !== false && $block !== null) {
                        if ($block === $functionName) {
                            $functionName = null;

                            if ($isInMethod) {
                                $this->collector->currentMethodStop();
                                $isInMethod = false;
                            }
                        } elseif ($block === $className) {
                            $className = null;
                            $testClass = false;
                            $this->collector->currentClassStop();
                            $this->collector->currentClassReset();
                        }
                    }
                }

                continue;
            }

            [$token, $value] = $tokens[$i];

            switch ($token) {
                case T_NAMESPACE:
                    $namespace = $this->getNamespaceName($tokens, $i);
                    $this->collector->addNamespace($namespace);
                    $isLogicalLine = false;

                    break;

                case T_CLASS:
                case T_INTERFACE:
                case T_TRAIT:
                    if (!$this->isClassDeclaration($tokens, $i)) {
                        break;
                    }

                    $this->collector->currentClassReset();
                    $this->collector->currentClassIncrementComplexity();
                    $className    = $this->getClassName($namespace, $tokens, $i);
                    $currentBlock = T_CLASS;

                    if ($token === T_TRAIT) {
                        $this->collector->incrementTraits();
                    } elseif ($token === T_INTERFACE) {
                        $this->collector->incrementInterfaces();
                    } else {
                        if ($countTests && $this->isTestClass($className)) {
                            $testClass = true;
                            $this->collector->incrementTestClasses();
                        } else {
                            $classModifierToken = $this->getPreviousNonWhitespaceNonCommentTokenPos($tokens, $i);

                            if ($classModifierToken !== false &&
                                $tokens[$classModifierToken][0] === T_ABSTRACT
                            ) {
                                $this->collector->incrementAbstractClasses();
                            } elseif (
                                $classModifierToken !== false &&
                                $tokens[$classModifierToken][0] === T_FINAL
                            ) {
                                $this->collector->incrementFinalClasses();
                            } else {
                                $this->collector->incrementNonFinalClasses();
                            }
                        }
                    }

                    break;

                case T_FUNCTION:
                    $prev = $this->getPreviousNonWhitespaceTokenPos($tokens, $i);

                    if ($tokens[$prev][0] === T_USE) {
                        break;
                    }

                    $currentBlock = T_FUNCTION;

                    $next = $this->getNextNonWhitespaceTokenPos($tokens, $i);

                    if ($tokens[$next] === '&' || (is_array($tokens[$next]) && $tokens[$next][1] === '&')) {
                        $next = $this->getNextNonWhitespaceTokenPos($tokens, $next);
                    }

                    if (is_array($tokens[$next]) &&
                        $tokens[$next][0] === T_STRING) {
                        $functionName = $tokens[$next][1];
                    } else {
                        $currentBlock = 'anonymous function';
                        $functionName = 'anonymous function';
                        $this->collector->incrementAnonymousFunctions();
                    }

                    if ($currentBlock === T_FUNCTION) {
                        if ($className === null &&
                            $functionName !== 'anonymous function') {
                            $this->collector->incrementNamedFunctions();
                        } else {
                            $static     = false;
                            $visibility = T_PUBLIC;

                            for ($j = $i; $j > 0; $j--) {
                                if (is_string($tokens[$j])) {
                                    if ($tokens[$j] === '{' ||
                                        $tokens[$j] === '}' ||
                                        $tokens[$j] === ';') {
                                        break;
                                    }

                                    continue;
                                }

                                if (isset($tokens[$j][0])) {
                                    switch ($tokens[$j][0]) {
                                        case T_PRIVATE:
                                            $visibility = T_PRIVATE;

                                            break;

                                        case T_PROTECTED:
                                            $visibility = T_PROTECTED;

                                            break;

                                        case T_STATIC:
                                            $static = true;

                                            break;
                                    }
                                }
                            }

                            if ($testClass &&
                                $this->isTestMethod($functionName, $visibility, $static, $tokens, $i)) {
                                $this->collector->incrementTestMethods();
                            } elseif (!$testClass) {
                                $isInMethod = true;
                                $this->collector->currentMethodStart();

                                $this->collector->currentClassIncrementMethods();

                                if (!$static) {
                                    $this->collector->incrementNonStaticMethods();
                                } else {
                                    $this->collector->incrementStaticMethods();
                                }

                                if ($visibility === T_PUBLIC) {
                                    $this->collector->incrementPublicMethods();
                                } elseif ($visibility === T_PROTECTED) {
                                    $this->collector->incrementProtectedMethods();
                                } elseif ($visibility === T_PRIVATE) {
                                    $this->collector->incrementPrivateMethods();
                                }
                            }
                        }
                    }

                    break;

                case T_CURLY_OPEN:
                    $currentBlock = T_CURLY_OPEN;
                    $blocks[]     = $currentBlock;

                    break;

                case T_DOLLAR_OPEN_CURLY_BRACES:
                    $currentBlock = T_DOLLAR_OPEN_CURLY_BRACES;
                    $blocks[]     = $currentBlock;

                    break;

                case T_IF:
                case T_ELSEIF:
                case T_FOR:
                case T_FOREACH:
                case T_WHILE:
                case T_CASE:
                case T_CATCH:
                case T_BOOLEAN_AND:
                case T_LOGICAL_AND:
                case T_BOOLEAN_OR:
                case T_LOGICAL_OR:
                    if (!$testClass) {
                        if ($isInMethod) {
                            $this->collector->currentClassIncrementComplexity();
                            $this->collector->currentMethodIncrementComplexity();
                        }

                        $this->collector->incrementComplexity();
                    }

                    break;

                case T_COMMENT:
                case T_DOC_COMMENT:
                    // We want to count all intermediate lines before the token ends
                    // But sometimes a new token starts after a newline, we don't want to count that.
                    // That happened with /* */ and /**  */, but not with // since it'll end at the end
                    $this->collector->incrementCommentLines(substr_count(rtrim($value, "\n"), "\n") + 1);

                    break;
                case T_CONST:
                    $possibleScopeToken = $this->getPreviousNonWhitespaceNonCommentTokenPos($tokens, $i);

                    if ($possibleScopeToken !== false &&
                        in_array($tokens[$possibleScopeToken][0], [T_PRIVATE, T_PROTECTED], true)
                    ) {
                        $this->collector->incrementNonPublicClassConstants();
                    } else {
                        $this->collector->incrementPublicClassConstants();
                    }

                    break;

                case T_STRING:
                    if ($value === 'define') {
                        $this->collector->incrementGlobalConstants();

                        $j = $i + 1;

                        while (isset($tokens[$j]) && $tokens[$j] !== ';') {
                            if (is_array($tokens[$j]) &&
                                $tokens[$j][0] === T_CONSTANT_ENCAPSED_STRING) {
                                $this->collector->addConstant(str_replace('\'', '', $tokens[$j][1]));

                                break;
                            }

                            $j++;
                        }
                    } else {
                        $this->collector->addPossibleConstantAccesses($value);
                    }

                    break;

                case T_DOUBLE_COLON:
                case T_OBJECT_OPERATOR:
                    $n  = $this->getNextNonWhitespaceTokenPos($tokens, $i);
                    $nn = $this->getNextNonWhitespaceTokenPos($tokens, $n);

                    if ($n && $nn &&
                        isset($tokens[$n][0]) &&
                        ($tokens[$n][0] === T_STRING ||
                         $tokens[$n][0] === T_VARIABLE) &&
                        $tokens[$nn] === '(') {
                        if ($token === T_DOUBLE_COLON) {
                            $this->collector->incrementStaticMethodCalls();
                        } else {
                            $this->collector->incrementNonStaticMethodCalls();
                        }
                    } else {
                        if ($token === T_DOUBLE_COLON &&
                            $tokens[$n][0] === T_VARIABLE) {
                            $this->collector->incrementStaticAttributeAccesses();
                        } elseif ($token === T_OBJECT_OPERATOR) {
                            $this->collector->incrementNonStaticAttributeAccesses();
                        }
                    }

                    break;

                case T_GLOBAL:
                    $this->collector->incrementGlobalVariableAccesses();

                    break;

                case T_VARIABLE:
                    if ($value === '$GLOBALS') {
                        $this->collector->incrementGlobalVariableAccesses();
                    } elseif (isset($this->superGlobals[$value])) {
                        $this->collector->incrementSuperGlobalVariableAccesses();
                    }

                    break;

                case T_USE:
                case T_DECLARE:
                    $isLogicalLine = false;

                    break;
            }
        }
    }

    /**
     * @param int $i
     *
     * @return string
     */
    private function getNamespaceName(array $tokens, $i)
    {
        if (isset($tokens[$i + 2][1])) {
            $namespace = $tokens[$i + 2][1];

            for ($j = $i + 3; ; $j += 2) {
                if (isset($tokens[$j]) && $tokens[$j][0] === T_NS_SEPARATOR) {
                    $namespace .= '\\' . $tokens[$j + 1][1];
                } else {
                    break;
                }
            }

            return $namespace;
        }

        return false;
    }

    /**
     * @param string $namespace
     * @param int    $i
     *
     * @return string
     */
    private function getClassName($namespace, array $tokens, $i)
    {
        $i += 2;

        if (!isset($tokens[$i][1])) {
            return 'invalid class name';
        }

        $className = $tokens[$i][1];

        $namespaced = $className === '\\';

        while (isset($tokens[$i + 1]) && is_array($tokens[$i + 1]) && $tokens[$i + 1][0] !== T_WHITESPACE) {
            $className .= $tokens[++$i][1];
        }

        if (!$namespaced && $namespace !== false) {
            $className = $namespace . '\\' . $className;
        }

        return strtolower($className);
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    private function isTestClass($className)
    {
        $parent = $this->classes[$className];
        $count  = 0;

        // Check ancestry for PHPUnit_Framework_TestCase.
        while ($parent !== null) {
            $count++;

            if ($count > 100) {
                // Prevent infinite loops and just bail
                break;
            }

            if ($parent === 'phpunit_framework_testcase' ||
                $parent === '\\phpunit_framework_testcase' ||
                // TODO: Recognize PHPUnit\Framework\TestCase when it is imported
                $parent === 'phpunit\\framework\\testcase' ||
                $parent === '\\phpunit\\framework\\testcase') {
                return true;
            }

            if (isset($this->classes[$parent]) && $parent !== $this->classes[$parent]) {
                $parent = $this->classes[$parent];
            } else {
                // Class has a parent that is declared in a file
                // that was not pre-processed.
                break;
            }
        }

        // Fallback: Treat the class as a test case class if the name
        // of the parent class ends with "TestCase".
        return substr((string) $this->classes[$className], -8) === 'testcase';
    }

    /**
     * @param string $functionName
     * @param int    $visibility
     * @param bool   $static
     * @param int    $currentToken
     *
     * @return bool
     */
    private function isTestMethod($functionName, $visibility, $static, array $tokens, $currentToken)
    {
        if ($static || $visibility != T_PUBLIC) {
            return false;
        }

        if (strpos($functionName, 'test') === 0) {
            return true;
        }

        while ($tokens[$currentToken][0] !== T_DOC_COMMENT) {
            if ($tokens[$currentToken] === '{' || $tokens[$currentToken] === '}') {
                return false;
            }

            $currentToken--;
        }

        return strpos($tokens[$currentToken][1], '@test') !== false ||
               strpos($tokens[$currentToken][1], '@scenario') !== false;
    }

    /**
     * @param int $start
     *
     * @return bool
     */
    private function getNextNonWhitespaceTokenPos(array $tokens, $start)
    {
        if (isset($tokens[$start + 1])) {
            if (isset($tokens[$start + 1][0]) &&
                $tokens[$start + 1][0] === T_WHITESPACE &&
                isset($tokens[$start + 2])) {
                return $start + 2;
            }

            return $start + 1;
        }

        return false;
    }

    /**
     * @param int $start
     *
     * @return bool
     */
    private function getPreviousNonWhitespaceTokenPos(array $tokens, $start)
    {
        if (isset($tokens[$start - 1])) {
            if (isset($tokens[$start - 1][0]) &&
                $tokens[$start - 1][0] === T_WHITESPACE &&
                isset($tokens[$start - 2])) {
                return $start - 2;
            }

            return $start - 1;
        }

        return false;
    }

    /**
     * @return bool
     */
    private function getPreviousNonWhitespaceNonCommentTokenPos(array $tokens, int $start)
    {
        $previousTokenIndex = $start - 1;

        if (isset($tokens[$previousTokenIndex])) {
            if (in_array($tokens[$previousTokenIndex][0], [
                T_WHITESPACE,
                T_COMMENT,
                T_DOC_COMMENT,
            ], true)
            ) {
                return $this->getPreviousNonWhitespaceNonCommentTokenPos($tokens, $previousTokenIndex);
            }

            return $previousTokenIndex;
        }

        return false;
    }

    /**
     * @param int $i
     *
     * @return bool
     */
    private function isClassDeclaration(array $tokens, $i)
    {
        $n = $this->getPreviousNonWhitespaceTokenPos($tokens, $i);

        return !isset($tokens[$n]) ||
            !is_array($tokens[$n]) ||
            !in_array($tokens[$n][0], [T_DOUBLE_COLON, T_NEW], true);
    }
}
