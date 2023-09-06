<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2023 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Formatter;

use Psy\Reflection\ReflectionClassConstant;
use Psy\Reflection\ReflectionConstant_;
use Psy\Reflection\ReflectionLanguageConstruct;
use Psy\Util\Json;
use Symfony\Component\Console\Formatter\OutputFormatter;

/**
 * An abstract representation of a function, class or property signature.
 */
class SignatureFormatter implements ReflectorFormatter
{
    /**
     * Format a signature for the given reflector.
     *
     * Defers to subclasses to do the actual formatting.
     *
     * @param \Reflector $reflector
     *
     * @return string Formatted signature
     */
    public static function format(\Reflector $reflector): string
    {
        switch (true) {
            case $reflector instanceof \ReflectionFunction:
            case $reflector instanceof ReflectionLanguageConstruct:
                return self::formatFunction($reflector);

            case $reflector instanceof \ReflectionClass:
                // this case also covers \ReflectionObject
                return self::formatClass($reflector);

            case $reflector instanceof ReflectionClassConstant:
            case $reflector instanceof \ReflectionClassConstant:
                return self::formatClassConstant($reflector);

            case $reflector instanceof \ReflectionMethod:
                return self::formatMethod($reflector);

            case $reflector instanceof \ReflectionProperty:
                return self::formatProperty($reflector);

            case $reflector instanceof ReflectionConstant_:
                return self::formatConstant($reflector);

            default:
                throw new \InvalidArgumentException('Unexpected Reflector class: '.\get_class($reflector));
        }
    }

    /**
     * Print the signature name.
     *
     * @param \ReflectionClass|ReflectionClassConstant|\ReflectionClassConstant|\ReflectionFunctionAbstract $reflector
     *
     * @return string Formatted name
     */
    public static function formatName(\Reflector $reflector): string
    {
        return $reflector->getName();
    }

    /**
     * Print the method, property or class modifiers.
     *
     * @param \ReflectionMethod|\ReflectionProperty|\ReflectionClass $reflector
     *
     * @return string Formatted modifiers
     */
    private static function formatModifiers(\Reflector $reflector): string
    {
        return \implode(' ', \array_map(function ($modifier) {
            return \sprintf('<keyword>%s</keyword>', $modifier);
        }, \Reflection::getModifierNames($reflector->getModifiers())));
    }

    /**
     * Format a class signature.
     *
     * @param \ReflectionClass $reflector
     *
     * @return string Formatted signature
     */
    private static function formatClass(\ReflectionClass $reflector): string
    {
        $chunks = [];

        if ($modifiers = self::formatModifiers($reflector)) {
            $chunks[] = $modifiers;
        }

        if ($reflector->isTrait()) {
            $chunks[] = 'trait';
        } else {
            $chunks[] = $reflector->isInterface() ? 'interface' : 'class';
        }

        $chunks[] = \sprintf('<class>%s</class>', self::formatName($reflector));

        if ($parent = $reflector->getParentClass()) {
            $chunks[] = 'extends';
            $chunks[] = \sprintf('<class>%s</class>', $parent->getName());
        }

        $interfaces = $reflector->getInterfaceNames();
        if (!empty($interfaces)) {
            \sort($interfaces);

            $chunks[] = $reflector->isInterface() ? 'extends' : 'implements';
            $chunks[] = \implode(', ', \array_map(function ($name) {
                return \sprintf('<class>%s</class>', $name);
            }, $interfaces));
        }

        return \implode(' ', $chunks);
    }

    /**
     * Format a constant signature.
     *
     * @param ReflectionClassConstant|\ReflectionClassConstant $reflector
     *
     * @return string Formatted signature
     */
    private static function formatClassConstant($reflector): string
    {
        $value = $reflector->getValue();
        $style = self::getTypeStyle($value);

        return \sprintf(
            '<keyword>const</keyword> <const>%s</const> = <%s>%s</%s>',
            self::formatName($reflector),
            $style,
            OutputFormatter::escape(Json::encode($value)),
            $style
        );
    }

    /**
     * Format a constant signature.
     *
     * @param ReflectionConstant_ $reflector
     *
     * @return string Formatted signature
     */
    private static function formatConstant(ReflectionConstant_ $reflector): string
    {
        $value = $reflector->getValue();
        $style = self::getTypeStyle($value);

        return \sprintf(
            '<keyword>define</keyword>(<string>%s</string>, <%s>%s</%s>)',
            OutputFormatter::escape(Json::encode($reflector->getName())),
            $style,
            OutputFormatter::escape(Json::encode($value)),
            $style
        );
    }

    /**
     * Helper for getting output style for a given value's type.
     *
     * @param mixed $value
     */
    private static function getTypeStyle($value): string
    {
        if (\is_int($value) || \is_float($value)) {
            return 'number';
        } elseif (\is_string($value)) {
            return 'string';
        } elseif (\is_bool($value) || $value === null) {
            return 'bool';
        } else {
            return 'strong'; // @codeCoverageIgnore
        }
    }

    /**
     * Format a property signature.
     *
     * @param \ReflectionProperty $reflector
     *
     * @return string Formatted signature
     */
    private static function formatProperty(\ReflectionProperty $reflector): string
    {
        return \sprintf(
            '%s <strong>$%s</strong>',
            self::formatModifiers($reflector),
            $reflector->getName()
        );
    }

    /**
     * Format a function signature.
     *
     * @param \ReflectionFunction $reflector
     *
     * @return string Formatted signature
     */
    private static function formatFunction(\ReflectionFunctionAbstract $reflector): string
    {
        return \sprintf(
            '<keyword>function</keyword> %s<function>%s</function>(%s)%s',
            $reflector->returnsReference() ? '&' : '',
            self::formatName($reflector),
            \implode(', ', self::formatFunctionParams($reflector)),
            self::formatFunctionReturnType($reflector)
        );
    }

    /**
     * Format a function signature's return type (if available).
     *
     * @param \ReflectionFunctionAbstract $reflector
     *
     * @return string Formatted return type
     */
    private static function formatFunctionReturnType(\ReflectionFunctionAbstract $reflector): string
    {
        if (!\method_exists($reflector, 'hasReturnType') || !$reflector->hasReturnType()) {
            return '';
        }

        return \sprintf(': %s', self::formatReflectionType($reflector->getReturnType()));
    }

    /**
     * Format a method signature.
     *
     * @param \ReflectionMethod $reflector
     *
     * @return string Formatted signature
     */
    private static function formatMethod(\ReflectionMethod $reflector): string
    {
        return \sprintf(
            '%s %s',
            self::formatModifiers($reflector),
            self::formatFunction($reflector)
        );
    }

    /**
     * Print the function params.
     *
     * @param \ReflectionFunctionAbstract $reflector
     *
     * @return array
     */
    private static function formatFunctionParams(\ReflectionFunctionAbstract $reflector): array
    {
        $params = [];
        foreach ($reflector->getParameters() as $param) {
            $hint = '';
            try {
                if (\method_exists($param, 'getType')) {
                    $hint = self::formatReflectionType($param->getType());
                } else {
                    if ($param->isArray()) {
                        $hint = '<keyword>array</keyword>';
                    } elseif ($class = $param->getClass()) {
                        $hint = \sprintf('<class>%s</class>', $class->getName());
                    }
                }
            } catch (\Throwable $e) {
                // sometimes we just don't know...
                // bad class names, or autoloaded classes that haven't been loaded yet, or whathaveyou.
                // come to think of it, the only time I've seen this is with the intl extension.

                // Hax: we'll try to extract it :P

                // @codeCoverageIgnoreStart
                $chunks = \explode('$'.$param->getName(), (string) $param);
                $chunks = \explode(' ', \trim($chunks[0]));
                $guess = \end($chunks);

                $hint = \sprintf('<urgent>%s</urgent>', OutputFormatter::escape($guess));
                // @codeCoverageIgnoreEnd
            }

            if ($param->isOptional()) {
                if (!$param->isDefaultValueAvailable()) {
                    $value = 'unknown';
                    $typeStyle = 'urgent';
                } else {
                    $value = $param->getDefaultValue();
                    $typeStyle = self::getTypeStyle($value);
                    $value = \is_array($value) ? '[]' : ($value === null ? 'null' : \var_export($value, true));
                }
                $default = \sprintf(' = <%s>%s</%s>', $typeStyle, OutputFormatter::escape($value), $typeStyle);
            } else {
                $default = '';
            }

            $params[] = \sprintf(
                '%s%s%s<strong>$%s</strong>%s',
                $param->isPassedByReference() ? '&' : '',
                $hint,
                $hint !== '' ? ' ' : '',
                $param->getName(),
                $default
            );
        }

        return $params;
    }

    /**
     * Print function param or return type(s).
     *
     * @param \ReflectionType $type
     */
    private static function formatReflectionType(\ReflectionType $type = null): string
    {
        if ($type === null) {
            return '';
        }

        $types = $type instanceof \ReflectionUnionType ? $type->getTypes() : [$type];
        $formattedTypes = [];

        foreach ($types as $type) {
            $typeStyle = $type->isBuiltin() ? 'keyword' : 'class';

            // PHP 7.0 didn't have `getName` on reflection types, so wheee!
            $typeName = \method_exists($type, 'getName') ? $type->getName() : (string) $type;

            // @todo Do we want to include the ? for nullable types? Maybe only sometimes?
            $formattedTypes[] = \sprintf('<%s>%s</%s>', $typeStyle, OutputFormatter::escape($typeName), $typeStyle);
        }

        return \implode('|', $formattedTypes);
    }
}
