<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2023 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Command;

use PhpParser\NodeTraverser;
use PhpParser\PrettyPrinter\Standard as Printer;
use Psy\CodeCleaner\NoReturnValue;
use Psy\Context;
use Psy\ContextAware;
use Psy\Exception\ErrorException;
use Psy\Exception\RuntimeException;
use Psy\Exception\UnexpectedTargetException;
use Psy\ParserFactory;
use Psy\Reflection\ReflectionClassConstant;
use Psy\Reflection\ReflectionConstant_;
use Psy\Sudo\SudoVisitor;
use Psy\Util\Mirror;

/**
 * An abstract command with helpers for inspecting the current context.
 */
abstract class ReflectingCommand extends Command implements ContextAware
{
    const CLASS_OR_FUNC = '/^[\\\\\w]+$/';
    const CLASS_MEMBER = '/^([\\\\\w]+)::(\w+)$/';
    const CLASS_STATIC = '/^([\\\\\w]+)::\$(\w+)$/';
    const INSTANCE_MEMBER = '/^(\$\w+)(::|->)(\w+)$/';

    /**
     * Context instance (for ContextAware interface).
     *
     * @var Context
     */
    protected $context;

    private $parser;
    private $traverser;
    private $printer;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        $parserFactory = new ParserFactory();
        $this->parser = $parserFactory->createParser();

        $this->traverser = new NodeTraverser();
        $this->traverser->addVisitor(new SudoVisitor());

        $this->printer = new Printer();

        parent::__construct($name);
    }

    /**
     * ContextAware interface.
     *
     * @param Context $context
     */
    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    /**
     * Get the target for a value.
     *
     * @throws \InvalidArgumentException when the value specified can't be resolved
     *
     * @param string $valueName Function, class, variable, constant, method or property name
     *
     * @return array (class or instance name, member name, kind)
     */
    protected function getTarget(string $valueName): array
    {
        $valueName = \trim($valueName);
        $matches = [];
        switch (true) {
            case \preg_match(self::CLASS_OR_FUNC, $valueName, $matches):
                return [$this->resolveName($matches[0], true), null, 0];

            case \preg_match(self::CLASS_MEMBER, $valueName, $matches):
                return [$this->resolveName($matches[1]), $matches[2], Mirror::CONSTANT | Mirror::METHOD];

            case \preg_match(self::CLASS_STATIC, $valueName, $matches):
                return [$this->resolveName($matches[1]), $matches[2], Mirror::STATIC_PROPERTY | Mirror::PROPERTY];

            case \preg_match(self::INSTANCE_MEMBER, $valueName, $matches):
                if ($matches[2] === '->') {
                    $kind = Mirror::METHOD | Mirror::PROPERTY;
                } else {
                    $kind = Mirror::CONSTANT | Mirror::METHOD;
                }

                return [$this->resolveObject($matches[1]), $matches[3], $kind];

            default:
                return [$this->resolveObject($valueName), null, 0];
        }
    }

    /**
     * Resolve a class or function name (with the current shell namespace).
     *
     * @throws ErrorException when `self` or `static` is used in a non-class scope
     *
     * @param string $name
     * @param bool   $includeFunctions (default: false)
     */
    protected function resolveName(string $name, bool $includeFunctions = false): string
    {
        $shell = $this->getApplication();

        // While not *technically* 100% accurate, let's treat `self` and `static` as equivalent.
        if (\in_array(\strtolower($name), ['self', 'static'])) {
            if ($boundClass = $shell->getBoundClass()) {
                return $boundClass;
            }

            if ($boundObject = $shell->getBoundObject()) {
                return \get_class($boundObject);
            }

            $msg = \sprintf('Cannot use "%s" when no class scope is active', \strtolower($name));
            throw new ErrorException($msg, 0, \E_USER_ERROR, "eval()'d code", 1);
        }

        if (\substr($name, 0, 1) === '\\') {
            return $name;
        }

        // Check $name against the current namespace and use statements.
        if (self::couldBeClassName($name)) {
            try {
                $name = $this->resolveCode($name.'::class');
            } catch (RuntimeException $e) {
                // /shrug
            }
        }

        if ($namespace = $shell->getNamespace()) {
            $fullName = $namespace.'\\'.$name;

            if (\class_exists($fullName) || \interface_exists($fullName) || ($includeFunctions && \function_exists($fullName))) {
                return $fullName;
            }
        }

        return $name;
    }

    /**
     * Check whether a given name could be a class name.
     */
    protected function couldBeClassName(string $name): bool
    {
        // Regex based on https://www.php.net/manual/en/language.oop5.basic.php#language.oop5.basic.class
        return \preg_match('/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*(\\\\[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)*$/', $name) === 1;
    }

    /**
     * Get a Reflector and documentation for a function, class or instance, constant, method or property.
     *
     * @param string $valueName Function, class, variable, constant, method or property name
     *
     * @return array (value, Reflector)
     */
    protected function getTargetAndReflector(string $valueName): array
    {
        list($value, $member, $kind) = $this->getTarget($valueName);

        return [$value, Mirror::get($value, $member, $kind)];
    }

    /**
     * Resolve code to a value in the current scope.
     *
     * @throws RuntimeException when the code does not return a value in the current scope
     *
     * @param string $code
     *
     * @return mixed Variable value
     */
    protected function resolveCode(string $code)
    {
        try {
            // Add an implicit `sudo` to target resolution.
            $nodes = $this->traverser->traverse($this->parse($code));
            $sudoCode = $this->printer->prettyPrint($nodes);
            $value = $this->getApplication()->execute($sudoCode, true);
        } catch (\Throwable $e) {
            // Swallow all exceptions?
        }

        if (!isset($value) || $value instanceof NoReturnValue) {
            throw new RuntimeException('Unknown target: '.$code);
        }

        return $value;
    }

    /**
     * Lex and parse a string of code into statements.
     *
     * @param string $code
     *
     * @return array Statements
     */
    private function parse($code)
    {
        $code = '<?php '.$code;

        try {
            return $this->parser->parse($code);
        } catch (\PhpParser\Error $e) {
            if (\strpos($e->getMessage(), 'unexpected EOF') === false) {
                throw $e;
            }

            // If we got an unexpected EOF, let's try it again with a semicolon.
            return $this->parser->parse($code.';');
        }
    }

    /**
     * Resolve code to an object in the current scope.
     *
     * @throws UnexpectedTargetException when the code resolves to a non-object value
     *
     * @param string $code
     *
     * @return object Variable instance
     */
    private function resolveObject(string $code)
    {
        $value = $this->resolveCode($code);

        if (!\is_object($value)) {
            throw new UnexpectedTargetException($value, 'Unable to inspect a non-object');
        }

        return $value;
    }

    /**
     * @deprecated Use `resolveCode` instead
     *
     * @param string $name
     *
     * @return mixed Variable instance
     */
    protected function resolveInstance(string $name)
    {
        @\trigger_error('`resolveInstance` is deprecated; use `resolveCode` instead.', \E_USER_DEPRECATED);

        return $this->resolveCode($name);
    }

    /**
     * Get a variable from the current shell scope.
     *
     * @param string $name
     *
     * @return mixed
     */
    protected function getScopeVariable(string $name)
    {
        return $this->context->get($name);
    }

    /**
     * Get all scope variables from the current shell scope.
     *
     * @return array
     */
    protected function getScopeVariables(): array
    {
        return $this->context->getAll();
    }

    /**
     * Given a Reflector instance, set command-scope variables in the shell
     * execution context. This is used to inject magic $__class, $__method and
     * $__file variables (as well as a handful of others).
     *
     * @param \Reflector $reflector
     */
    protected function setCommandScopeVariables(\Reflector $reflector)
    {
        $vars = [];

        switch (\get_class($reflector)) {
            case \ReflectionClass::class:
            case \ReflectionObject::class:
                $vars['__class'] = $reflector->name;
                if ($reflector->inNamespace()) {
                    $vars['__namespace'] = $reflector->getNamespaceName();
                }
                break;

            case \ReflectionMethod::class:
                $vars['__method'] = \sprintf('%s::%s', $reflector->class, $reflector->name);
                $vars['__class'] = $reflector->class;
                $classReflector = $reflector->getDeclaringClass();
                if ($classReflector->inNamespace()) {
                    $vars['__namespace'] = $classReflector->getNamespaceName();
                }
                break;

            case \ReflectionFunction::class:
                $vars['__function'] = $reflector->name;
                if ($reflector->inNamespace()) {
                    $vars['__namespace'] = $reflector->getNamespaceName();
                }
                break;

            case \ReflectionGenerator::class:
                $funcReflector = $reflector->getFunction();
                $vars['__function'] = $funcReflector->name;
                if ($funcReflector->inNamespace()) {
                    $vars['__namespace'] = $funcReflector->getNamespaceName();
                }
                if ($fileName = $reflector->getExecutingFile()) {
                    $vars['__file'] = $fileName;
                    $vars['__line'] = $reflector->getExecutingLine();
                    $vars['__dir'] = \dirname($fileName);
                }
                break;

            case \ReflectionProperty::class:
            case \ReflectionClassConstant::class:
            case ReflectionClassConstant::class:
                $classReflector = $reflector->getDeclaringClass();
                $vars['__class'] = $classReflector->name;
                if ($classReflector->inNamespace()) {
                    $vars['__namespace'] = $classReflector->getNamespaceName();
                }
                // no line for these, but this'll do
                if ($fileName = $reflector->getDeclaringClass()->getFileName()) {
                    $vars['__file'] = $fileName;
                    $vars['__dir'] = \dirname($fileName);
                }
                break;

            case ReflectionConstant_::class:
                if ($reflector->inNamespace()) {
                    $vars['__namespace'] = $reflector->getNamespaceName();
                }
                break;
        }

        if ($reflector instanceof \ReflectionClass || $reflector instanceof \ReflectionFunctionAbstract) {
            if ($fileName = $reflector->getFileName()) {
                $vars['__file'] = $fileName;
                $vars['__line'] = $reflector->getStartLine();
                $vars['__dir'] = \dirname($fileName);
            }
        }

        $this->context->setCommandScopeVariables($vars);
    }
}
