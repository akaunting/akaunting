<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFloatNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprStringNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeParameterNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use function array_merge;
use function count;
use function in_array;
use function preg_replace;
use function strtolower;
use function substr;

/**
 * @internal
 */
class AnnotationTypeHelper
{

	/**
	 * @param TypeNode $typeNode
	 * @return IdentifierTypeNode[]|ThisTypeNode[]
	 */
	public static function getIdentifierTypeNodes(TypeNode $typeNode): array
	{
		if ($typeNode instanceof ArrayTypeNode) {
			return self::getIdentifierTypeNodes($typeNode->type);
		}

		if ($typeNode instanceof ArrayShapeNode) {
			$identifierTypeNodes = [];
			foreach ($typeNode->items as $arrayShapeItemNode) {
				$identifierTypeNodes = array_merge($identifierTypeNodes, self::getIdentifierTypeNodes($arrayShapeItemNode->valueType));
			}
			return $identifierTypeNodes;
		}

		if (
			$typeNode instanceof UnionTypeNode
			|| $typeNode instanceof IntersectionTypeNode
		) {
			$identifierTypeNodes = [];
			foreach ($typeNode->types as $innerTypeNode) {
				$identifierTypeNodes = array_merge($identifierTypeNodes, self::getIdentifierTypeNodes($innerTypeNode));
			}
			return $identifierTypeNodes;
		}

		if ($typeNode instanceof GenericTypeNode) {
			$identifierTypeNodes = self::getIdentifierTypeNodes($typeNode->type);
			foreach ($typeNode->genericTypes as $innerTypeNode) {
				$identifierTypeNodes = array_merge($identifierTypeNodes, self::getIdentifierTypeNodes($innerTypeNode));
			}
			return $identifierTypeNodes;
		}

		if ($typeNode instanceof NullableTypeNode) {
			return self::getIdentifierTypeNodes($typeNode->type);
		}

		if ($typeNode instanceof CallableTypeNode) {
			$identifierTypeNodes = array_merge([$typeNode->identifier], self::getIdentifierTypeNodes($typeNode->returnType));
			foreach ($typeNode->parameters as $callableParameterNode) {
				$identifierTypeNodes = array_merge($identifierTypeNodes, self::getIdentifierTypeNodes($callableParameterNode->type));
			}
			return $identifierTypeNodes;
		}

		if ($typeNode instanceof ConstTypeNode) {
			return [];
		}

		/** @var IdentifierTypeNode|ThisTypeNode $typeNode */
		$typeNode = $typeNode;
		return [$typeNode];
	}

	/**
	 * @param TypeNode $typeNode
	 * @return ConstTypeNode[]
	 */
	public static function getConstantTypeNodes(TypeNode $typeNode): array
	{
		if ($typeNode instanceof ArrayTypeNode) {
			return self::getConstantTypeNodes($typeNode->type);
		}

		if ($typeNode instanceof ArrayShapeNode) {
			$constTypeNodes = [];
			foreach ($typeNode->items as $arrayShapeItemNode) {
				$constTypeNodes = array_merge($constTypeNodes, self::getConstantTypeNodes($arrayShapeItemNode->valueType));
			}
			return $constTypeNodes;
		}

		if (
			$typeNode instanceof UnionTypeNode
			|| $typeNode instanceof IntersectionTypeNode
		) {
			$constTypeNodes = [];
			foreach ($typeNode->types as $innerTypeNode) {
				$constTypeNodes = array_merge($constTypeNodes, self::getConstantTypeNodes($innerTypeNode));
			}
			return $constTypeNodes;
		}

		if ($typeNode instanceof GenericTypeNode) {
			$constTypeNodes = [];
			foreach ($typeNode->genericTypes as $innerTypeNode) {
				$constTypeNodes = array_merge($constTypeNodes, self::getConstantTypeNodes($innerTypeNode));
			}
			return $constTypeNodes;
		}

		if ($typeNode instanceof NullableTypeNode) {
			return self::getConstantTypeNodes($typeNode->type);
		}

		if ($typeNode instanceof CallableTypeNode) {
			$constTypeNodes = self::getConstantTypeNodes($typeNode->returnType);
			foreach ($typeNode->parameters as $callableParameterNode) {
				$constTypeNodes = array_merge($constTypeNodes, self::getConstantTypeNodes($callableParameterNode->type));
			}
			return $constTypeNodes;
		}

		if (!$typeNode instanceof ConstTypeNode) {
			return [];
		}

		return [$typeNode];
	}

	/**
	 * @param TypeNode $typeNode
	 * @return UnionTypeNode[]
	 */
	public static function getUnionTypeNodes(TypeNode $typeNode): array
	{
		if ($typeNode instanceof UnionTypeNode) {
			return [$typeNode];
		}

		if ($typeNode instanceof NullableTypeNode) {
			return self::getUnionTypeNodes($typeNode->type);
		}

		if ($typeNode instanceof ArrayTypeNode) {
			return self::getUnionTypeNodes($typeNode->type);
		}

		if ($typeNode instanceof ArrayShapeNode) {
			$unionTypeNodes = [];
			foreach ($typeNode->items as $arrayShapeItemNode) {
				$unionTypeNodes = array_merge($unionTypeNodes, self::getUnionTypeNodes($arrayShapeItemNode->valueType));
			}
			return $unionTypeNodes;
		}

		if ($typeNode instanceof IntersectionTypeNode) {
			$unionTypeNodes = [];
			foreach ($typeNode->types as $innerTypeNode) {
				$unionTypeNodes = array_merge($unionTypeNodes, self::getUnionTypeNodes($innerTypeNode));
			}
			return $unionTypeNodes;
		}

		if ($typeNode instanceof GenericTypeNode) {
			$unionTypeNodes = [];
			foreach ($typeNode->genericTypes as $innerTypeNode) {
				$unionTypeNodes = array_merge($unionTypeNodes, self::getUnionTypeNodes($innerTypeNode));
			}
			return $unionTypeNodes;
		}

		if ($typeNode instanceof CallableTypeNode) {
			$unionTypeNodes = self::getUnionTypeNodes($typeNode->returnType);
			foreach ($typeNode->parameters as $callableParameterNode) {
				$unionTypeNodes = array_merge($unionTypeNodes, self::getUnionTypeNodes($callableParameterNode->type));
			}
			return $unionTypeNodes;
		}

		return [];
	}

	/**
	 * @param TypeNode $typeNode
	 * @return ArrayTypeNode[]
	 */
	public static function getArrayTypeNodes(TypeNode $typeNode): array
	{
		if ($typeNode instanceof ArrayTypeNode) {
			return array_merge([$typeNode], self::getArrayTypeNodes($typeNode->type));
		}

		if ($typeNode instanceof ArrayShapeNode) {
			$arrayTypeNodes = [];
			foreach ($typeNode->items as $arrayShapeItemNode) {
				$arrayTypeNodes = array_merge($arrayTypeNodes, self::getArrayTypeNodes($arrayShapeItemNode->valueType));
			}
			return $arrayTypeNodes;
		}

		if ($typeNode instanceof NullableTypeNode) {
			return self::getArrayTypeNodes($typeNode->type);
		}

		if (
			$typeNode instanceof UnionTypeNode
			|| $typeNode instanceof IntersectionTypeNode
		) {
			$arrayTypeNodes = [];
			foreach ($typeNode->types as $innerTypeNode) {
				$arrayTypeNodes = array_merge($arrayTypeNodes, self::getArrayTypeNodes($innerTypeNode));
			}
			return $arrayTypeNodes;
		}

		if ($typeNode instanceof GenericTypeNode) {
			$arrayTypeNodes = [];
			foreach ($typeNode->genericTypes as $innerTypeNode) {
				$arrayTypeNodes = array_merge($arrayTypeNodes, self::getArrayTypeNodes($innerTypeNode));
			}
			return $arrayTypeNodes;
		}

		if ($typeNode instanceof CallableTypeNode) {
			$arrayTypeNodes = self::getArrayTypeNodes($typeNode->returnType);
			foreach ($typeNode->parameters as $callableParameterNode) {
				$arrayTypeNodes = array_merge($arrayTypeNodes, self::getArrayTypeNodes($callableParameterNode->type));
			}
			return $arrayTypeNodes;
		}

		return [];
	}

	/**
	 * @param IdentifierTypeNode|ThisTypeNode $typeNode
	 * @return string
	 */
	public static function getTypeHintFromNode(TypeNode $typeNode): string
	{
		return $typeNode instanceof ThisTypeNode
			? (string) $typeNode
			: $typeNode->name;
	}

	public static function export(TypeNode $typeNode): string
	{
		$exportedTypeNode = preg_replace(['~\\s*(&|\|)\\s*~'], '\\1', (string) $typeNode);

		if (
			$typeNode instanceof UnionTypeNode
			|| $typeNode instanceof IntersectionTypeNode
		) {
			$exportedTypeNode = substr($exportedTypeNode, 1, -1);
		}

		return $exportedTypeNode;
	}

	public static function change(TypeNode $masterTypeNode, TypeNode $typeNodeToChange, TypeNode $changedTypeNode): TypeNode
	{
		if ($masterTypeNode === $typeNodeToChange) {
			return $changedTypeNode;
		}

		if ($masterTypeNode instanceof UnionTypeNode) {
			$types = [];
			foreach ($masterTypeNode->types as $typeNone) {
				$types[] = self::change($typeNone, $typeNodeToChange, $changedTypeNode);
			}

			return new UnionTypeNode($types);
		}

		if ($masterTypeNode instanceof IntersectionTypeNode) {
			$types = [];
			foreach ($masterTypeNode->types as $typeNone) {
				$types[] = self::change($typeNone, $typeNodeToChange, $changedTypeNode);
			}

			return new IntersectionTypeNode($types);
		}

		if ($masterTypeNode instanceof GenericTypeNode) {
			$genericTypes = [];
			foreach ($masterTypeNode->genericTypes as $genericTypeNode) {
				$genericTypes[] = self::change($genericTypeNode, $typeNodeToChange, $changedTypeNode);
			}

			/** @var IdentifierTypeNode $identificatorTypeNode */
			$identificatorTypeNode = self::change($masterTypeNode->type, $typeNodeToChange, $changedTypeNode);
			return new GenericTypeNode($identificatorTypeNode, $genericTypes);
		}

		if ($masterTypeNode instanceof ArrayTypeNode) {
			return new ArrayTypeNode(self::change($masterTypeNode->type, $typeNodeToChange, $changedTypeNode));
		}

		if ($masterTypeNode instanceof ArrayShapeNode) {
			$arrayShapeItemNodes = [];
			foreach ($masterTypeNode->items as $arrayShapeItemNode) {
				$arrayShapeItemNodes[] = self::change($arrayShapeItemNode, $typeNodeToChange, $changedTypeNode);
			}

			return new ArrayShapeNode($arrayShapeItemNodes);
		}

		if ($masterTypeNode instanceof ArrayShapeItemNode) {
			return new ArrayShapeItemNode(
				$masterTypeNode->keyName,
				$masterTypeNode->optional,
				self::change($masterTypeNode->valueType, $typeNodeToChange, $changedTypeNode)
			);
		}

		if ($masterTypeNode instanceof NullableTypeNode) {
			return new NullableTypeNode(self::change($masterTypeNode->type, $typeNodeToChange, $changedTypeNode));
		}

		if ($masterTypeNode instanceof CallableTypeNode) {
			$callableParameters = [];
			foreach ($masterTypeNode->parameters as $parameterTypeNode) {
				$callableParameters[] = new CallableTypeParameterNode(
					self::change($parameterTypeNode->type, $typeNodeToChange, $changedTypeNode),
					$parameterTypeNode->isReference,
					$parameterTypeNode->isVariadic,
					$parameterTypeNode->parameterName,
					$parameterTypeNode->isOptional
				);
			}

			/** @var IdentifierTypeNode $identificatorTypeNode */
			$identificatorTypeNode = self::change($masterTypeNode->identifier, $typeNodeToChange, $changedTypeNode);
			return new CallableTypeNode(
				$identificatorTypeNode,
				$callableParameters,
				self::change($masterTypeNode->returnType, $typeNodeToChange, $changedTypeNode)
			);
		}

		return clone $masterTypeNode;
	}

	/**
	 * @param UnionTypeNode|IntersectionTypeNode $typeNode
	 * @return bool
	 */
	public static function containsNullType(TypeNode $typeNode): bool
	{
		foreach ($typeNode->types as $innerTypeNode) {
			if (!$innerTypeNode instanceof IdentifierTypeNode) {
				continue;
			}

			if (strtolower($innerTypeNode->name) === 'null') {
				return true;
			}
		}

		return false;
	}

	public static function containsStaticOrThisType(TypeNode $typeNode): bool
	{
		if ($typeNode instanceof ThisTypeNode) {
			return true;
		}

		if ($typeNode instanceof IdentifierTypeNode) {
			return strtolower($typeNode->name) === 'static';
		}

		if (
			$typeNode instanceof UnionTypeNode
			|| $typeNode instanceof IntersectionTypeNode
		) {
			foreach ($typeNode->types as $innerTypeNode) {
				if (self::containsStaticOrThisType($innerTypeNode)) {
					return true;
				}
			}
		}

		return false;
	}

	public static function containsOneType(TypeNode $typeNode): bool
	{
		if ($typeNode instanceof IdentifierTypeNode) {
			return true;
		}

		if ($typeNode instanceof ThisTypeNode) {
			return true;
		}

		if ($typeNode instanceof GenericTypeNode) {
			return true;
		}

		if ($typeNode instanceof CallableTypeNode) {
			return true;
		}

		if ($typeNode instanceof ArrayShapeNode) {
			return true;
		}

		if ($typeNode instanceof ArrayTypeNode) {
			return true;
		}

		if ($typeNode instanceof ConstTypeNode) {
			if ($typeNode->constExpr instanceof ConstExprIntegerNode) {
				return true;
			}

			if ($typeNode->constExpr instanceof ConstExprFloatNode) {
				return true;
			}

			if ($typeNode->constExpr instanceof ConstExprStringNode) {
				return true;
			}
		}

		return false;
	}

	public static function containsJustTwoTypes(TypeNode $typeNode): bool
	{
		if (
			!$typeNode instanceof UnionTypeNode
			&& !$typeNode instanceof IntersectionTypeNode
		) {
			return false;
		}

		return count($typeNode->types) === 2;
	}

	public static function isCompoundOfNull(TypeNode $typeNode): bool
	{
		if (!self::containsJustTwoTypes($typeNode)) {
			return false;
		}

		/** @var UnionTypeNode|IntersectionTypeNode $typeNode */
		$typeNode = $typeNode;
		return self::containsNullType($typeNode);
	}

	/**
	 * @param TypeNode $typeNode
	 * @param File $phpcsFile
	 * @param int $pointer
	 * @param array<int, string> $traversableTypeHints
	 * @return bool
	 */
	public static function containsTraversableType(TypeNode $typeNode, File $phpcsFile, int $pointer, array $traversableTypeHints): bool
	{
		if ($typeNode instanceof GenericTypeNode) {
			return true;
		}

		if ($typeNode instanceof ArrayShapeNode) {
			return true;
		}

		if ($typeNode instanceof ArrayTypeNode) {
			return true;
		}

		if ($typeNode instanceof IdentifierTypeNode) {
			$fullyQualifiedType = TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $pointer, $typeNode->name);
			return TypeHintHelper::isTraversableType($fullyQualifiedType, $traversableTypeHints);
		}

		if (
			$typeNode instanceof UnionTypeNode
			|| $typeNode instanceof IntersectionTypeNode
		) {
			foreach ($typeNode->types as $innerTypeNode) {
				if (self::containsTraversableType($innerTypeNode, $phpcsFile, $pointer, $traversableTypeHints)) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @param TypeNode $typeNode
	 * @param File $phpcsFile
	 * @param int $pointer
	 * @param array<int, string> $traversableTypeHints
	 * @param bool $inTraversable
	 * @return bool
	 */
	public static function containsItemsSpecificationForTraversable(
		TypeNode $typeNode,
		File $phpcsFile,
		int $pointer,
		array $traversableTypeHints,
		bool $inTraversable = false
	): bool
	{
		if ($typeNode instanceof GenericTypeNode) {
			foreach ($typeNode->genericTypes as $genericType) {
				if (!self::containsItemsSpecificationForTraversable($genericType, $phpcsFile, $pointer, $traversableTypeHints, true)) {
					return false;
				}
			}

			return true;
		}

		if ($typeNode instanceof ArrayShapeNode) {
			foreach ($typeNode->items as $arrayShapeItemNode) {
				if (!self::containsItemsSpecificationForTraversable(
					$arrayShapeItemNode->valueType,
					$phpcsFile,
					$pointer,
					$traversableTypeHints,
					true
				)) {
					return false;
				}
			}

			return true;
		}

		if ($typeNode instanceof NullableTypeNode) {
			return self::containsItemsSpecificationForTraversable($typeNode->type, $phpcsFile, $pointer, $traversableTypeHints, true);
		}

		if ($typeNode instanceof IdentifierTypeNode) {
			if (!$inTraversable) {
				return false;
			}

			return !TypeHintHelper::isTraversableType(
				TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $pointer, $typeNode->name),
				$traversableTypeHints
			);
		}

		if ($typeNode instanceof CallableTypeNode) {
			return $inTraversable;
		}

		if ($typeNode instanceof ArrayTypeNode) {
			return self::containsItemsSpecificationForTraversable($typeNode->type, $phpcsFile, $pointer, $traversableTypeHints, true);
		}

		if (
			$typeNode instanceof UnionTypeNode
			|| $typeNode instanceof IntersectionTypeNode
		) {
			foreach ($typeNode->types as $innerTypeNode) {
				if (
					!$inTraversable
					&& $innerTypeNode instanceof IdentifierTypeNode
					&& strtolower($innerTypeNode->name) === 'null'
				) {
					continue;
				}

				if (self::containsItemsSpecificationForTraversable(
					$innerTypeNode,
					$phpcsFile,
					$pointer,
					$traversableTypeHints,
					$inTraversable
				)) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @param UnionTypeNode|IntersectionTypeNode $typeNode
	 * @return TypeNode
	 */
	public static function getTypeFromNullableType(TypeNode $typeNode): TypeNode
	{
		return $typeNode->types[0] instanceof IdentifierTypeNode && strtolower($typeNode->types[0]->name) === 'null'
			? $typeNode->types[1]
			: $typeNode->types[0];
	}

	/**
	 * @param CallableTypeNode|GenericTypeNode|IdentifierTypeNode|ThisTypeNode|ArrayTypeNode|ArrayShapeNode|ConstTypeNode $typeNode
	 * @return string
	 */
	public static function getTypeHintFromOneType(TypeNode $typeNode): string
	{
		if ($typeNode instanceof GenericTypeNode) {
			return $typeNode->type->name;
		}

		if ($typeNode instanceof IdentifierTypeNode) {
			if (strtolower($typeNode->name) === 'true') {
				return 'bool';
			}

			if (strtolower($typeNode->name) === 'false') {
				return 'bool';
			}

			if (in_array(strtolower($typeNode->name), ['class-string', 'trait-string', 'callable-string', 'numeric-string'], true)) {
				return 'string';
			}

			return $typeNode->name;
		}

		if ($typeNode instanceof CallableTypeNode) {
			return $typeNode->identifier->name;
		}

		if ($typeNode instanceof ArrayTypeNode) {
			return 'array';
		}

		if ($typeNode instanceof ArrayShapeNode) {
			return 'array';
		}

		if ($typeNode instanceof ConstTypeNode) {
			if ($typeNode->constExpr instanceof ConstExprIntegerNode) {
				return 'int';
			}

			if ($typeNode->constExpr instanceof ConstExprFloatNode) {
				return 'float';
			}

			if ($typeNode->constExpr instanceof ConstExprStringNode) {
				return 'string';
			}
		}

		return (string) $typeNode;
	}

	/**
	 * @param UnionTypeNode|IntersectionTypeNode $typeNode
	 * @param File $phpcsFile
	 * @param int $pointer
	 * @param array<int, string> $traversableTypeHints
	 * @return string|null
	 */
	public static function getTraversableTypeHintFromType(
		TypeNode $typeNode,
		File $phpcsFile,
		int $pointer,
		array $traversableTypeHints
	): ?string
	{
		$typeHint = null;

		foreach ($typeNode->types as $type) {
			if (
				$type instanceof GenericTypeNode
				|| $type instanceof ThisTypeNode
				|| $type instanceof IdentifierTypeNode
			) {
				$typeHint = self::getTypeHintFromOneType($type);
				break;
			}
		}

		return $typeHint !== null && TypeHintHelper::isTraversableType(
			TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $pointer, $typeHint),
			$traversableTypeHints
		)
			? $typeHint
			: null;
	}

	/**
	 * @param UnionTypeNode|IntersectionTypeNode $typeNode
	 * @return ?TypeNode
	 */
	public static function getItemsSpecificationTypeFromType(TypeNode $typeNode): ?TypeNode
	{
		foreach ($typeNode->types as $type) {
			if ($type instanceof ArrayTypeNode) {
				return $type;
			}
		}

		return null;
	}

}
