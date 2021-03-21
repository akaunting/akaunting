<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use Generator;
use PHP_CodeSniffer\Files\File;
use SlevomatCodingStandard\Helpers\Annotation\ParameterAnnotation;
use SlevomatCodingStandard\Helpers\Annotation\ReturnAnnotation;
use function array_filter;
use function array_map;
use function array_merge;
use function array_reverse;
use function count;
use function in_array;
use function iterator_to_array;
use function sprintf;
use const T_ANON_CLASS;
use const T_BITWISE_AND;
use const T_CLASS;
use const T_CLOSURE;
use const T_COLON;
use const T_COMMA;
use const T_ELLIPSIS;
use const T_EQUAL;
use const T_FUNCTION;
use const T_INLINE_THEN;
use const T_INTERFACE;
use const T_NULLABLE;
use const T_RETURN;
use const T_SEMICOLON;
use const T_STRING;
use const T_TRAIT;
use const T_VARIABLE;
use const T_YIELD;
use const T_YIELD_FROM;

class FunctionHelper
{

	public const SPECIAL_FUNCTIONS = [
		'array_key_exists',
		'array_slice',
		'boolval',
		'call_user_func',
		'call_user_func_array',
		'chr',
		'count',
		'doubleval',
		'defined',
		'floatval',
		'func_get_args',
		'func_num_args',
		'get_called_class',
		'get_class',
		'gettype',
		'in_array',
		'intval',
		'is_array',
		'is_bool',
		'is_double',
		'is_float',
		'is_long',
		'is_int',
		'is_integer',
		'is_null',
		'is_object',
		'is_real',
		'is_resource',
		'is_string',
		'ord',
		'sizeof',
		'strlen',
		'strval',
	];

	public static function getTypeLabel(File $phpcsFile, int $functionPointer): string
	{
		return self::isMethod($phpcsFile, $functionPointer) ? 'Method' : 'Function';
	}

	public static function getName(File $phpcsFile, int $functionPointer): string
	{
		$tokens = $phpcsFile->getTokens();
		return $tokens[TokenHelper::findNext(
			$phpcsFile,
			T_STRING,
			$functionPointer + 1,
			$tokens[$functionPointer]['parenthesis_opener']
		)]['content'];
	}

	public static function getFullyQualifiedName(File $phpcsFile, int $functionPointer): string
	{
		$name = self::getName($phpcsFile, $functionPointer);
		$namespace = NamespaceHelper::findCurrentNamespaceName($phpcsFile, $functionPointer);

		if (self::isMethod($phpcsFile, $functionPointer)) {
			foreach (array_reverse(
				$phpcsFile->getTokens()[$functionPointer]['conditions'],
				true
			) as $conditionPointer => $conditionTokenCode) {
				if ($conditionTokenCode === T_ANON_CLASS) {
					return sprintf('class@anonymous::%s', $name);
				}

				if (in_array($conditionTokenCode, [T_CLASS, T_INTERFACE, T_TRAIT], true)) {
					$name = sprintf(
						'%s%s::%s',
						NamespaceHelper::NAMESPACE_SEPARATOR,
						ClassHelper::getName($phpcsFile, $conditionPointer),
						$name
					);
					break;
				}
			}

			return $namespace !== null ? sprintf('%s%s%s', NamespaceHelper::NAMESPACE_SEPARATOR, $namespace, $name) : $name;
		}

		return $namespace !== null
			? sprintf('%s%s%s%s', NamespaceHelper::NAMESPACE_SEPARATOR, $namespace, NamespaceHelper::NAMESPACE_SEPARATOR, $name)
			: $name;
	}

	public static function isAbstract(File $phpcsFile, int $functionPointer): bool
	{
		return !isset($phpcsFile->getTokens()[$functionPointer]['scope_opener']);
	}

	public static function isMethod(File $phpcsFile, int $functionPointer): bool
	{
		foreach (array_reverse($phpcsFile->getTokens()[$functionPointer]['conditions']) as $conditionTokenCode) {
			if (!in_array($conditionTokenCode, [T_CLASS, T_INTERFACE, T_TRAIT, T_ANON_CLASS], true)) {
				continue;
			}

			return true;
		}

		return false;
	}

	public static function findClassPointer(File $phpcsFile, int $functionPointer): ?int
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$functionPointer]['code'] === T_CLOSURE) {
			return null;
		}

		foreach (array_reverse($tokens[$functionPointer]['conditions'], true) as $conditionPointer => $conditionTokenCode) {
			if (!in_array($conditionTokenCode, [T_CLASS, T_INTERFACE, T_TRAIT, T_ANON_CLASS], true)) {
				continue;
			}

			return $conditionPointer;
		}

		return null;
	}

	/**
	 * @param File $phpcsFile
	 * @param int $functionPointer
	 * @return string[]
	 */
	public static function getParametersNames(File $phpcsFile, int $functionPointer): array
	{
		$tokens = $phpcsFile->getTokens();

		$parametersNames = [];
		for ($i = $tokens[$functionPointer]['parenthesis_opener'] + 1; $i < $tokens[$functionPointer]['parenthesis_closer']; $i++) {
			if ($tokens[$i]['code'] !== T_VARIABLE) {
				continue;
			}

			$parametersNames[] = $tokens[$i]['content'];
		}

		return $parametersNames;
	}

	/**
	 * @param File $phpcsFile
	 * @param int $functionPointer
	 * @return (ParameterTypeHint|null)[]
	 */
	public static function getParametersTypeHints(File $phpcsFile, int $functionPointer): array
	{
		$tokens = $phpcsFile->getTokens();

		$parametersTypeHints = [];
		for ($i = $tokens[$functionPointer]['parenthesis_opener'] + 1; $i < $tokens[$functionPointer]['parenthesis_closer']; $i++) {
			if ($tokens[$i]['code'] !== T_VARIABLE) {
				continue;
			}

			$parameterName = $tokens[$i]['content'];
			$typeHint = '';
			$isNullable = false;

			$previousToken = $i;
			do {
				$previousToken = TokenHelper::findPreviousExcluding(
					$phpcsFile,
					array_merge(TokenHelper::$ineffectiveTokenCodes, [T_BITWISE_AND, T_ELLIPSIS]),
					$previousToken - 1,
					$tokens[$functionPointer]['parenthesis_opener'] + 1
				);
				if ($previousToken !== null) {
					// PHPCS reports T_NULLABLE as T_INLINE_THEN in PHP 8
					$isTypeHint = !in_array($tokens[$previousToken]['code'], [T_COMMA, T_NULLABLE, T_INLINE_THEN], true);
					if (in_array($tokens[$previousToken]['code'], [T_NULLABLE, T_INLINE_THEN], true)) {
						$isNullable = true;
					}
				} else {
					$isTypeHint = false;
				}

				if ($isTypeHint) {
					$typeHint = $tokens[$previousToken]['content'] . $typeHint;
				}

			} while ($isTypeHint);

			$equalsPointer = TokenHelper::findNextEffective($phpcsFile, $i + 1, $tokens[$functionPointer]['parenthesis_closer']);
			$isOptional = $equalsPointer !== null && $tokens[$equalsPointer]['code'] === T_EQUAL;

			$parametersTypeHints[$parameterName] = $typeHint !== '' ? new ParameterTypeHint($typeHint, $isNullable, $isOptional) : null;
		}

		return $parametersTypeHints;
	}

	public static function returnsValue(File $phpcsFile, int $functionPointer): bool
	{
		$tokens = $phpcsFile->getTokens();

		$firstPointerInScope = $tokens[$functionPointer]['scope_opener'] + 1;

		for ($i = $firstPointerInScope; $i < $tokens[$functionPointer]['scope_closer']; $i++) {
			if (!in_array($tokens[$i]['code'], [T_YIELD, T_YIELD_FROM], true)) {
				continue;
			}

			if (!ScopeHelper::isInSameScope($phpcsFile, $i, $firstPointerInScope)) {
				continue;
			}

			return true;
		}

		for ($i = $firstPointerInScope; $i < $tokens[$functionPointer]['scope_closer']; $i++) {
			if ($tokens[$i]['code'] !== T_RETURN) {
				continue;
			}

			if (!ScopeHelper::isInSameScope($phpcsFile, $i, $firstPointerInScope)) {
				continue;
			}

			$nextEffectiveTokenPointer = TokenHelper::findNextEffective($phpcsFile, $i + 1);
			return $tokens[$nextEffectiveTokenPointer]['code'] !== T_SEMICOLON;
		}

		return false;
	}

	public static function findReturnTypeHint(File $phpcsFile, int $functionPointer): ?ReturnTypeHint
	{
		$tokens = $phpcsFile->getTokens();

		$isAbstract = self::isAbstract($phpcsFile, $functionPointer);

		$colonToken = $isAbstract
			? TokenHelper::findNextLocal($phpcsFile, T_COLON, $tokens[$functionPointer]['parenthesis_closer'] + 1)
			: TokenHelper::findNext(
				$phpcsFile,
				T_COLON,
				$tokens[$functionPointer]['parenthesis_closer'] + 1,
				$tokens[$functionPointer]['scope_opener'] - 1
			);

		if ($colonToken === null) {
			return null;
		}

		$abstractExcludeTokens = array_merge(TokenHelper::$ineffectiveTokenCodes, [T_SEMICOLON]);

		$nullableToken = $isAbstract
			? TokenHelper::findNextLocalExcluding($phpcsFile, $abstractExcludeTokens, $colonToken + 1)
			: TokenHelper::findNextExcluding(
				$phpcsFile,
				TokenHelper::$ineffectiveTokenCodes,
				$colonToken + 1,
				$tokens[$functionPointer]['scope_opener'] - 1
			);

		$nullable = $nullableToken !== null && $tokens[$nullableToken]['code'] === T_NULLABLE;

		$typeHint = '';
		$typeHintStartPointer = null;
		$typeHintEndPointer = null;
		$nextToken = $nullable ? $nullableToken : $colonToken;
		do {
			$nextToken = $isAbstract
				? TokenHelper::findNextLocalExcluding($phpcsFile, $abstractExcludeTokens, $nextToken + 1)
				: TokenHelper::findNextExcluding(
					$phpcsFile,
					TokenHelper::$ineffectiveTokenCodes,
					$nextToken + 1,
					$tokens[$functionPointer]['scope_opener']
				);

			$isTypeHint = $nextToken !== null;
			if (!$isTypeHint) {
				break;
			}

			$typeHint .= $tokens[$nextToken]['content'];
			if ($typeHintStartPointer === null) {
				/** @var int $typeHintStartPointer */
				$typeHintStartPointer = $nextToken;
			}
			/** @var int $typeHintEndPointer */
			$typeHintEndPointer = $nextToken;
		} while ($isTypeHint);

		return $typeHint !== '' ? new ReturnTypeHint($typeHint, $nullable, $typeHintStartPointer, $typeHintEndPointer) : null;
	}

	public static function hasReturnTypeHint(File $phpcsFile, int $functionPointer): bool
	{
		return self::findReturnTypeHint($phpcsFile, $functionPointer) !== null;
	}

	/**
	 * @param File $phpcsFile
	 * @param int $functionPointer
	 * @return ParameterAnnotation[]
	 */
	public static function getParametersAnnotations(File $phpcsFile, int $functionPointer): array
	{
		/** @var ParameterAnnotation[] $parametersAnnotations */
		$parametersAnnotations = AnnotationHelper::getAnnotationsByName($phpcsFile, $functionPointer, '@param');
		return $parametersAnnotations;
	}

	/**
	 * @param File $phpcsFile
	 * @param int $functionPointer
	 * @return array<string, ParameterAnnotation>
	 */
	public static function getValidParametersAnnotations(File $phpcsFile, int $functionPointer): array
	{
		$parametersAnnotations = [];
		foreach (self::getParametersAnnotations($phpcsFile, $functionPointer) as $parameterAnnotation) {
			if ($parameterAnnotation->getContent() === null) {
				continue;
			}

			if ($parameterAnnotation->isInvalid()) {
				continue;
			}

			$parametersAnnotations[$parameterAnnotation->getParameterName()] = $parameterAnnotation;
		}

		return $parametersAnnotations;
	}

	/**
	 * @param File $phpcsFile
	 * @param int $functionPointer
	 * @return array<string, ParameterAnnotation>
	 */
	public static function getValidPrefixedParametersAnnotations(File $phpcsFile, int $functionPointer): array
	{
		$parametersAnnotations = [];
		foreach (AnnotationHelper::PREFIXES as $prefix) {
			/** @var ParameterAnnotation[] $annotations */
			$annotations = AnnotationHelper::getAnnotationsByName($phpcsFile, $functionPointer, sprintf('@%s-param', $prefix));
			foreach ($annotations as $parameterAnnotation) {
				if ($parameterAnnotation->getContent() === null) {
					continue;
				}

				if ($parameterAnnotation->isInvalid()) {
					continue;
				}

				$parametersAnnotations[$parameterAnnotation->getParameterName()] = $parameterAnnotation;
			}
		}

		return $parametersAnnotations;
	}

	public static function findReturnAnnotation(File $phpcsFile, int $functionPointer): ?ReturnAnnotation
	{
		/** @var ReturnAnnotation[] $returnAnnotations */
		$returnAnnotations = AnnotationHelper::getAnnotationsByName($phpcsFile, $functionPointer, '@return');

		if (count($returnAnnotations) === 0) {
			return null;
		}

		return $returnAnnotations[0];
	}

	/**
	 * @param File $phpcsFile
	 * @param int $functionPointer
	 * @return ReturnAnnotation[]
	 */
	public static function getValidPrefixedReturnAnnotations(File $phpcsFile, int $functionPointer): array
	{
		$returnAnnotations = [];

		foreach (AnnotationHelper::PREFIXES as $prefix) {
			/** @var ReturnAnnotation[] $annotations */
			$annotations = AnnotationHelper::getAnnotationsByName($phpcsFile, $functionPointer, sprintf('@%s-return', $prefix));
			foreach ($annotations as $annotation) {
				if (!$annotation->isInvalid()) {
					$returnAnnotations[] = $annotation;
					break;
				}
			}
		}

		return $returnAnnotations;
	}

	/**
	 * @param File $phpcsFile
	 * @return string[]
	 */
	public static function getAllFunctionNames(File $phpcsFile): array
	{
		$previousFunctionPointer = 0;

		return array_map(
			static function (int $functionPointer) use ($phpcsFile): string {
				return self::getName($phpcsFile, $functionPointer);
			},
			array_filter(
				iterator_to_array(self::getAllFunctionOrMethodPointers($phpcsFile, $previousFunctionPointer)),
				static function (int $functionOrMethodPointer) use ($phpcsFile): bool {
					return !self::isMethod($phpcsFile, $functionOrMethodPointer);
				}
			)
		);
	}

	/**
	 * @param File $phpcsFile
	 * @param int $previousFunctionPointer
	 * @return Generator<int>
	 */
	private static function getAllFunctionOrMethodPointers(File $phpcsFile, int &$previousFunctionPointer): Generator
	{
		do {
			$nextFunctionPointer = TokenHelper::findNext($phpcsFile, T_FUNCTION, $previousFunctionPointer + 1);
			if ($nextFunctionPointer === null) {
				break;
			}

			$previousFunctionPointer = $nextFunctionPointer;

			yield $nextFunctionPointer;
		} while (true);
	}

}
