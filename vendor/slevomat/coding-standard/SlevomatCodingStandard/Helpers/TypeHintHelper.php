<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use SlevomatCodingStandard\Helpers\Annotation\TemplateAnnotation;
use function array_key_exists;
use function array_merge;
use function in_array;
use function sprintf;
use const T_FUNCTION;

class TypeHintHelper
{

	public static function isValidTypeHint(string $typeHint, bool $enableObjectTypeHint): bool
	{
		if (self::isSimpleTypeHint($typeHint)) {
			return true;
		}

		if ($typeHint === 'object') {
			return $enableObjectTypeHint;
		}

		return !self::isSimpleUnofficialTypeHints($typeHint);
	}

	public static function isSimpleTypeHint(string $typeHint): bool
	{
		return in_array($typeHint, self::getSimpleTypeHints(), true);
	}

	public static function isSimpleIterableTypeHint(string $typeHint): bool
	{
		return in_array($typeHint, self::getSimpleIterableTypeHints(), true);
	}

	public static function convertLongSimpleTypeHintToShort(string $typeHint): string
	{
		$longToShort = [
			'integer' => 'int',
			'boolean' => 'bool',
		];
		return array_key_exists($typeHint, $longToShort) ? $longToShort[$typeHint] : $typeHint;
	}

	public static function isTemplate(File $phpcsFile, int $docCommentOpenPointer, string $typeHint): bool
	{
		static $templateAnnotationNames = null;
		if ($templateAnnotationNames === null) {
			foreach (['template', 'template-covariant'] as $annotationName) {
				$templateAnnotationNames[] = sprintf('@%s', $annotationName);
				foreach (AnnotationHelper::PREFIXES as $prefixAnnotatioName) {
					$templateAnnotationNames[] = sprintf('@%s-%s', $prefixAnnotatioName, $annotationName);
				}
			}
		}

		$containsTypeHintInTemplateAnnotation = static function (int $docCommentOpenPointer) use ($phpcsFile, $templateAnnotationNames, $typeHint): bool {
			$annotations = AnnotationHelper::getAnnotations($phpcsFile, $docCommentOpenPointer);
			foreach ($templateAnnotationNames as $templateAnnotationName) {
				if (!array_key_exists($templateAnnotationName, $annotations)) {
					continue;
				}

				/** @var TemplateAnnotation $templateAnnotation */
				foreach ($annotations[$templateAnnotationName] as $templateAnnotation) {
					if ($templateAnnotation->getTemplateName() === $typeHint) {
						return true;
					}
				}
			}

			return false;
		};

		$tokens = $phpcsFile->getTokens();

		$docCommentOwnerPointer = TokenHelper::findNext(
			$phpcsFile,
			array_merge([T_FUNCTION], TokenHelper::$typeKeywordTokenCodes),
			$tokens[$docCommentOpenPointer]['comment_closer'] + 1
		);
		if (
			$docCommentOwnerPointer !== null
			&& $tokens[$tokens[$docCommentOpenPointer]['comment_closer']]['line'] + 1 === $tokens[$docCommentOwnerPointer]['line']
		) {
			if ($containsTypeHintInTemplateAnnotation($docCommentOpenPointer)) {
				return true;
			}

			if ($tokens[$docCommentOwnerPointer]['code'] !== T_FUNCTION) {
				return false;
			}
		} else {
			$docCommentOwnerPointer = null;
		}

		$pointerToFindClass = $docCommentOpenPointer;
		if ($docCommentOwnerPointer === null) {
			$functionPointer = TokenHelper::findPrevious($phpcsFile, T_FUNCTION, $docCommentOpenPointer - 1);
			if ($functionPointer !== null) {
				$pointerToFindClass = $functionPointer;
			}
		}

		$classPointer = ClassHelper::getClassPointer($phpcsFile, $pointerToFindClass);

		if ($classPointer === null) {
			return false;
		}

		$classDocCommentOpenPointer = DocCommentHelper::findDocCommentOpenToken($phpcsFile, $classPointer);
		if ($classDocCommentOpenPointer === null) {
			return false;
		}

		return $containsTypeHintInTemplateAnnotation($classDocCommentOpenPointer);
	}

	public static function getFullyQualifiedTypeHint(File $phpcsFile, int $pointer, string $typeHint): string
	{
		if (self::isSimpleTypeHint($typeHint)) {
			return self::convertLongSimpleTypeHintToShort($typeHint);
		}

		return NamespaceHelper::resolveClassName($phpcsFile, $typeHint, $pointer);
	}

	/**
	 * @return string[]
	 */
	public static function getSimpleTypeHints(): array
	{
		static $simpleTypeHints;

		if ($simpleTypeHints === null) {
			$simpleTypeHints = [
				'int',
				'integer',
				'float',
				'string',
				'bool',
				'boolean',
				'callable',
				'self',
				'array',
				'iterable',
				'void',
			];
		}

		return $simpleTypeHints;
	}

	/**
	 * @return string[]
	 */
	public static function getSimpleIterableTypeHints(): array
	{
		return [
			'array',
			'iterable',
		];
	}

	public static function isSimpleUnofficialTypeHints(string $typeHint): bool
	{
		static $simpleUnofficialTypeHints;

		if ($simpleUnofficialTypeHints === null) {
			$simpleUnofficialTypeHints = [
				'null',
				'mixed',
				'scalar',
				'numeric',
				'true',
				'false',
				'object',
				'resource',
				'static',
				'$this',
				'class-string',
				'trait-string',
				'callable-string',
				'numeric-string',
				'array-key',
				'list',
				'empty',
			];
		}

		return in_array($typeHint, $simpleUnofficialTypeHints, true);
	}

	/**
	 * @param string $type
	 * @param string[] $traversableTypeHints
	 * @return bool
	 */
	public static function isTraversableType(string $type, array $traversableTypeHints): bool
	{
		return self::isSimpleIterableTypeHint($type) || in_array($type, $traversableTypeHints, true);
	}

	public static function typeHintEqualsAnnotation(
		File $phpcsFile,
		int $functionPointer,
		string $typeHint,
		string $typeHintInAnnotation
	): bool
	{
		return self::getFullyQualifiedTypeHint($phpcsFile, $functionPointer, $typeHint) === self::getFullyQualifiedTypeHint(
			$phpcsFile,
			$functionPointer,
			$typeHintInAnnotation
		);
	}

}
