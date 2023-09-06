<?php

declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Reflection;

use Doctrine\Deprecations\Deprecation;
use InvalidArgumentException;
use phpDocumentor\Reflection\PseudoTypes\ArrayShape;
use phpDocumentor\Reflection\PseudoTypes\ArrayShapeItem;
use phpDocumentor\Reflection\PseudoTypes\CallableString;
use phpDocumentor\Reflection\PseudoTypes\ConstExpression;
use phpDocumentor\Reflection\PseudoTypes\False_;
use phpDocumentor\Reflection\PseudoTypes\FloatValue;
use phpDocumentor\Reflection\PseudoTypes\HtmlEscapedString;
use phpDocumentor\Reflection\PseudoTypes\IntegerRange;
use phpDocumentor\Reflection\PseudoTypes\IntegerValue;
use phpDocumentor\Reflection\PseudoTypes\List_;
use phpDocumentor\Reflection\PseudoTypes\LiteralString;
use phpDocumentor\Reflection\PseudoTypes\LowercaseString;
use phpDocumentor\Reflection\PseudoTypes\NegativeInteger;
use phpDocumentor\Reflection\PseudoTypes\NonEmptyList;
use phpDocumentor\Reflection\PseudoTypes\NonEmptyLowercaseString;
use phpDocumentor\Reflection\PseudoTypes\NonEmptyString;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;
use phpDocumentor\Reflection\PseudoTypes\NumericString;
use phpDocumentor\Reflection\PseudoTypes\PositiveInteger;
use phpDocumentor\Reflection\PseudoTypes\StringValue;
use phpDocumentor\Reflection\PseudoTypes\TraitString;
use phpDocumentor\Reflection\PseudoTypes\True_;
use phpDocumentor\Reflection\Types\AggregatedType;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\ArrayKey;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Callable_;
use phpDocumentor\Reflection\Types\CallableParameter;
use phpDocumentor\Reflection\Types\ClassString;
use phpDocumentor\Reflection\Types\Collection;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\Expression;
use phpDocumentor\Reflection\Types\Float_;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\InterfaceString;
use phpDocumentor\Reflection\Types\Intersection;
use phpDocumentor\Reflection\Types\Iterable_;
use phpDocumentor\Reflection\Types\Mixed_;
use phpDocumentor\Reflection\Types\Never_;
use phpDocumentor\Reflection\Types\Null_;
use phpDocumentor\Reflection\Types\Nullable;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\Parent_;
use phpDocumentor\Reflection\Types\Resource_;
use phpDocumentor\Reflection\Types\Scalar;
use phpDocumentor\Reflection\Types\Self_;
use phpDocumentor\Reflection\Types\Static_;
use phpDocumentor\Reflection\Types\String_;
use phpDocumentor\Reflection\Types\This;
use phpDocumentor\Reflection\Types\Void_;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFloatNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprStringNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeParameterNode;
use PHPStan\PhpDocParser\Ast\Type\ConditionalTypeForParameterNode;
use PHPStan\PhpDocParser\Ast\Type\ConditionalTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\OffsetAccessTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\ParserException;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use RuntimeException;

use function array_filter;
use function array_key_exists;
use function array_map;
use function array_reverse;
use function class_exists;
use function class_implements;
use function get_class;
use function in_array;
use function sprintf;
use function strpos;
use function strtolower;
use function trim;

final class TypeResolver
{
    /** @var string Definition of the NAMESPACE operator in PHP */
    private const OPERATOR_NAMESPACE = '\\';

    /**
     * @var array<string, string> List of recognized keywords and unto which Value Object they map
     * @psalm-var array<string, class-string<Type>>
     */
    private array $keywords = [
        'string' => String_::class,
        'class-string' => ClassString::class,
        'interface-string' => InterfaceString::class,
        'html-escaped-string' => HtmlEscapedString::class,
        'lowercase-string' => LowercaseString::class,
        'non-empty-lowercase-string' => NonEmptyLowercaseString::class,
        'non-empty-string' => NonEmptyString::class,
        'numeric-string' => NumericString::class,
        'numeric' => Numeric_::class,
        'trait-string' => TraitString::class,
        'int' => Integer::class,
        'integer' => Integer::class,
        'positive-int' => PositiveInteger::class,
        'negative-int' => NegativeInteger::class,
        'bool' => Boolean::class,
        'boolean' => Boolean::class,
        'real' => Float_::class,
        'float' => Float_::class,
        'double' => Float_::class,
        'object' => Object_::class,
        'mixed' => Mixed_::class,
        'array' => Array_::class,
        'array-key' => ArrayKey::class,
        'resource' => Resource_::class,
        'void' => Void_::class,
        'null' => Null_::class,
        'scalar' => Scalar::class,
        'callback' => Callable_::class,
        'callable' => Callable_::class,
        'callable-string' => CallableString::class,
        'false' => False_::class,
        'true' => True_::class,
        'literal-string' => LiteralString::class,
        'self' => Self_::class,
        '$this' => This::class,
        'static' => Static_::class,
        'parent' => Parent_::class,
        'iterable' => Iterable_::class,
        'never' => Never_::class,
        'list' => List_::class,
        'non-empty-list' => NonEmptyList::class,
    ];

    /** @psalm-readonly */
    private FqsenResolver $fqsenResolver;
    /** @psalm-readonly */
    private TypeParser $typeParser;
    /** @psalm-readonly */
    private Lexer $lexer;

    /**
     * Initializes this TypeResolver with the means to create and resolve Fqsen objects.
     */
    public function __construct(?FqsenResolver $fqsenResolver = null)
    {
        $this->fqsenResolver = $fqsenResolver ?: new FqsenResolver();
        $this->typeParser = new TypeParser(new ConstExprParser());
        $this->lexer = new Lexer();
    }

    /**
     * Analyzes the given type and returns the FQCN variant.
     *
     * When a type is provided this method checks whether it is not a keyword or
     * Fully Qualified Class Name. If so it will use the given namespace and
     * aliases to expand the type to a FQCN representation.
     *
     * This method only works as expected if the namespace and aliases are set;
     * no dynamic reflection is being performed here.
     *
     * @uses Context::getNamespace()        to determine with what to prefix the type name.
     * @uses Context::getNamespaceAliases() to check whether the first part of the relative type name should not be
     * replaced with another namespace.
     *
     * @param string $type The relative or absolute type.
     */
    public function resolve(string $type, ?Context $context = null): Type
    {
        $type = trim($type);
        if (!$type) {
            throw new InvalidArgumentException('Attempted to resolve "' . $type . '" but it appears to be empty');
        }

        if ($context === null) {
            $context = new Context('');
        }

        $tokens = $this->lexer->tokenize($type);
        $tokenIterator = new TokenIterator($tokens);

        $ast = $this->parse($tokenIterator);
        $type = $this->createType($ast, $context);

        return $this->tryParseRemainingCompoundTypes($tokenIterator, $context, $type);
    }

    public function createType(?TypeNode $type, Context $context): Type
    {
        if ($type === null) {
            return new Mixed_();
        }

        switch (get_class($type)) {
            case ArrayTypeNode::class:
                return new Array_(
                    $this->createType($type->type, $context)
                );

            case ArrayShapeNode::class:
                return new ArrayShape(
                    ...array_map(
                        fn (ArrayShapeItemNode $item) => new ArrayShapeItem(
                            (string) $item->keyName,
                            $this->createType($item->valueType, $context),
                            $item->optional
                        ),
                        $type->items
                    )
                );

            case CallableTypeNode::class:
                return $this->createFromCallable($type, $context);

            case ConstTypeNode::class:
                return $this->createFromConst($type, $context);

            case GenericTypeNode::class:
                return $this->createFromGeneric($type, $context);

            case IdentifierTypeNode::class:
                return $this->resolveSingleType($type->name, $context);

            case IntersectionTypeNode::class:
                return new Intersection(
                    array_filter(
                        array_map(
                            function (TypeNode $nestedType) use ($context) {
                                $type = $this->createType($nestedType, $context);
                                if ($type instanceof AggregatedType) {
                                    return new Expression($type);
                                }

                                return $type;
                            },
                            $type->types
                        )
                    )
                );

            case NullableTypeNode::class:
                $nestedType = $this->createType($type->type, $context);

                return new Nullable($nestedType);

            case UnionTypeNode::class:
                return new Compound(
                    array_filter(
                        array_map(
                            function (TypeNode $nestedType) use ($context) {
                                $type = $this->createType($nestedType, $context);
                                if ($type instanceof AggregatedType) {
                                    return new Expression($type);
                                }

                                return $type;
                            },
                            $type->types
                        )
                    )
                );

            case ThisTypeNode::class:
                return new This();

            case ConditionalTypeNode::class:
            case ConditionalTypeForParameterNode::class:
            case OffsetAccessTypeNode::class:
            default:
                return new Mixed_();
        }
    }

    private function createFromGeneric(GenericTypeNode $type, Context $context): Type
    {
        switch (strtolower($type->type->name)) {
            case 'array':
                return $this->createArray($type->genericTypes, $context);

            case 'class-string':
                $subType = $this->createType($type->genericTypes[0], $context);
                if (!$subType instanceof Object_ || $subType->getFqsen() === null) {
                    throw new RuntimeException(
                        $subType . ' is not a class string'
                    );
                }

                return new ClassString(
                    $subType->getFqsen()
                );

            case 'interface-string':
                $subType = $this->createType($type->genericTypes[0], $context);
                if (!$subType instanceof Object_ || $subType->getFqsen() === null) {
                    throw new RuntimeException(
                        $subType . ' is not a class string'
                    );
                }

                return new InterfaceString(
                    $subType->getFqsen()
                );

            case 'list':
                return new List_(
                    $this->createType($type->genericTypes[0], $context)
                );

            case 'non-empty-list':
                return new NonEmptyList(
                    $this->createType($type->genericTypes[0], $context)
                );

            case 'int':
                if (isset($type->genericTypes[1]) === false) {
                    throw new RuntimeException('int<min,max> has not the correct format');
                }

                return new IntegerRange(
                    (string) $type->genericTypes[0],
                    (string) $type->genericTypes[1],
                );

            case 'iterable':
                return new Iterable_(
                    ...array_reverse(
                        array_map(
                            fn (TypeNode $genericType) => $this->createType($genericType, $context),
                            $type->genericTypes
                        )
                    )
                );

            default:
                $collectionType = $this->createType($type->type, $context);
                if ($collectionType instanceof Object_ === false) {
                    throw new RuntimeException(sprintf('%s is not a collection', (string) $collectionType));
                }

                return new Collection(
                    $collectionType->getFqsen(),
                    ...array_reverse(
                        array_map(
                            fn (TypeNode $genericType) => $this->createType($genericType, $context),
                            $type->genericTypes
                        )
                    )
                );
        }
    }

    private function createFromCallable(CallableTypeNode $type, Context $context): Callable_
    {
        return new Callable_(
            array_map(
                function (CallableTypeParameterNode $param) use ($context) {
                    return new CallableParameter(
                        $this->createType($param->type, $context),
                        $param->parameterName !== '' ? trim($param->parameterName, '$') : null,
                        $param->isReference,
                        $param->isVariadic,
                        $param->isOptional
                    );
                },
                $type->parameters
            ),
            $this->createType($type->returnType, $context),
        );
    }

    private function createFromConst(ConstTypeNode $type, Context $context): Type
    {
        switch (true) {
            case $type->constExpr instanceof ConstExprIntegerNode:
                return new IntegerValue((int) $type->constExpr->value);

            case $type->constExpr instanceof ConstExprFloatNode:
                return new FloatValue((float) $type->constExpr->value);

            case $type->constExpr instanceof ConstExprStringNode:
                return new StringValue($type->constExpr->value);

            case $type->constExpr instanceof ConstFetchNode:
                return new ConstExpression(
                    $this->resolve($type->constExpr->className, $context),
                    $type->constExpr->name
                );

            default:
                throw new RuntimeException(sprintf('Unsupported constant type %s', get_class($type)));
        }
    }

    /**
     * resolve the given type into a type object
     *
     * @param string $type the type string, representing a single type
     *
     * @return Type|Array_|Object_
     *
     * @psalm-mutation-free
     */
    private function resolveSingleType(string $type, Context $context): object
    {
        switch (true) {
            case $this->isKeyword($type):
                return $this->resolveKeyword($type);

            case $this->isFqsen($type):
                return $this->resolveTypedObject($type);

            case $this->isPartialStructuralElementName($type):
                return $this->resolveTypedObject($type, $context);

            // @codeCoverageIgnoreStart
            default:
                // I haven't got the foggiest how the logic would come here but added this as a defense.
                throw new RuntimeException(
                    'Unable to resolve type "' . $type . '", there is no known method to resolve it'
                );
        }

        // @codeCoverageIgnoreEnd
    }

    /**
     * Adds a keyword to the list of Keywords and associates it with a specific Value Object.
     *
     * @psalm-param class-string<Type> $typeClassName
     */
    public function addKeyword(string $keyword, string $typeClassName): void
    {
        if (!class_exists($typeClassName)) {
            throw new InvalidArgumentException(
                'The Value Object that needs to be created with a keyword "' . $keyword . '" must be an existing class'
                . ' but we could not find the class ' . $typeClassName
            );
        }

        $interfaces = class_implements($typeClassName);
        if ($interfaces === false) {
            throw new InvalidArgumentException(
                'The Value Object that needs to be created with a keyword "' . $keyword . '" must be an existing class'
                . ' but we could not find the class ' . $typeClassName
            );
        }

        if (!in_array(Type::class, $interfaces, true)) {
            throw new InvalidArgumentException(
                'The class "' . $typeClassName . '" must implement the interface "phpDocumentor\Reflection\Type"'
            );
        }

        $this->keywords[$keyword] = $typeClassName;
    }

    /**
     * Detects whether the given type represents a PHPDoc keyword.
     *
     * @param string $type A relative or absolute type as defined in the phpDocumentor documentation.
     *
     * @psalm-mutation-free
     */
    private function isKeyword(string $type): bool
    {
        return array_key_exists(strtolower($type), $this->keywords);
    }

    /**
     * Detects whether the given type represents a relative structural element name.
     *
     * @param string $type A relative or absolute type as defined in the phpDocumentor documentation.
     *
     * @psalm-mutation-free
     */
    private function isPartialStructuralElementName(string $type): bool
    {
        return (isset($type[0]) && $type[0] !== self::OPERATOR_NAMESPACE) && !$this->isKeyword($type);
    }

    /**
     * Tests whether the given type is a Fully Qualified Structural Element Name.
     *
     * @psalm-mutation-free
     */
    private function isFqsen(string $type): bool
    {
        return strpos($type, self::OPERATOR_NAMESPACE) === 0;
    }

    /**
     * Resolves the given keyword (such as `string`) into a Type object representing that keyword.
     *
     * @psalm-mutation-free
     */
    private function resolveKeyword(string $type): Type
    {
        $className = $this->keywords[strtolower($type)];

        return new $className();
    }

    /**
     * Resolves the given FQSEN string into an FQSEN object.
     *
     * @psalm-mutation-free
     */
    private function resolveTypedObject(string $type, ?Context $context = null): Object_
    {
        return new Object_($this->fqsenResolver->resolve($type, $context));
    }

    /** @param TypeNode[] $typeNodes */
    private function createArray(array $typeNodes, Context $context): Array_
    {
        $types = array_reverse(
            array_map(
                fn (TypeNode $node) => $this->createType($node, $context),
                $typeNodes
            )
        );

        if (isset($types[1]) === false) {
            return new Array_(...$types);
        }

        if ($this->validArrayKeyType($types[1]) || $types[1] instanceof ArrayKey) {
            return new Array_(...$types);
        }

        if ($types[1] instanceof Compound && $types[1]->getIterator()->count() === 2) {
            if ($this->validArrayKeyType($types[1]->get(0)) && $this->validArrayKeyType($types[1]->get(1))) {
                return new Array_(...$types);
            }
        }

        throw new RuntimeException('An array can have only integers or strings as keys');
    }

    private function validArrayKeyType(?Type $type): bool
    {
        return $type instanceof String_ || $type instanceof Integer;
    }

    private function parse(TokenIterator $tokenIterator): TypeNode
    {
        try {
            $ast = $this->typeParser->parse($tokenIterator);
        } catch (ParserException $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }

        return $ast;
    }

    /**
     * Will try to parse unsupported type notations by phpstan
     *
     * The phpstan parser doesn't support the illegal nullable combinations like this library does.
     * This method will warn the user about those notations but for bc purposes we will still have it here.
     */
    private function tryParseRemainingCompoundTypes(TokenIterator $tokenIterator, Context $context, Type $type): Type
    {
        if (
            $tokenIterator->isCurrentTokenType(Lexer::TOKEN_UNION) ||
            $tokenIterator->isCurrentTokenType(Lexer::TOKEN_INTERSECTION)
        ) {
            Deprecation::trigger(
                'phpdocumentor/type-resolver',
                'https://github.com/phpDocumentor/TypeResolver/issues/184',
                'Legacy nullable type detected, please update your code as
                you are using nullable types in a docblock. support will be removed in v2.0.0',
            );
        }

        $continue = true;
        while ($continue) {
            $continue = false;
            while ($tokenIterator->tryConsumeTokenType(Lexer::TOKEN_UNION)) {
                $ast = $this->parse($tokenIterator);
                $type2 = $this->createType($ast, $context);
                $type = new Compound([$type, $type2]);
                $continue = true;
            }

            while ($tokenIterator->tryConsumeTokenType(Lexer::TOKEN_INTERSECTION)) {
                $ast = $this->typeParser->parse($tokenIterator);
                $type2 = $this->createType($ast, $context);
                $type = new Intersection([$type, $type2]);
                $continue = true;
            }
        }

        return $type;
    }
}
