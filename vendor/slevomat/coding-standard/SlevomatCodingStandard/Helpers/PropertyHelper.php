<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use function array_key_exists;
use function array_keys;
use function array_merge;
use function array_reverse;
use function array_values;
use function count;
use function in_array;
use function sprintf;
use const T_ANON_CLASS;
use const T_CLOSE_CURLY_BRACKET;
use const T_NULLABLE;
use const T_OPEN_CURLY_BRACKET;
use const T_PRIVATE;
use const T_PROTECTED;
use const T_PUBLIC;
use const T_SEMICOLON;
use const T_STATIC;
use const T_VAR;

class PropertyHelper
{

	public static function isProperty(File $phpcsFile, int $variablePointer): bool
	{
		$tokens = $phpcsFile->getTokens();

		$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $variablePointer - 1);

		if ($tokens[$previousPointer]['code'] === T_STATIC) {
			$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $previousPointer - 1);
		}

		if (in_array($tokens[$previousPointer]['code'], [T_PUBLIC, T_PROTECTED, T_PRIVATE, T_VAR], true)) {
			return true;
		}

		if (
			!array_key_exists('conditions', $tokens[$variablePointer])
			|| count($tokens[$variablePointer]['conditions']) === 0
		) {
			return false;
		}

		$functionPointer = TokenHelper::findPrevious(
			$phpcsFile,
			array_merge(TokenHelper::$functionTokenCodes, [T_SEMICOLON, T_CLOSE_CURLY_BRACKET, T_OPEN_CURLY_BRACKET]),
			$variablePointer - 1
		);
		if (
			$functionPointer !== null
			&& in_array($tokens[$functionPointer]['code'], TokenHelper::$functionTokenCodes, true)
		) {
			return false;
		}

		$conditionCode = array_values($tokens[$variablePointer]['conditions'])[count($tokens[$variablePointer]['conditions']) - 1];

		return in_array($conditionCode, Tokens::$ooScopeTokens, true);
	}

	public static function findTypeHint(File $phpcsFile, int $propertyPointer): ?PropertyTypeHint
	{
		$tokens = $phpcsFile->getTokens();

		$propertyStartPointer = TokenHelper::findPrevious(
			$phpcsFile,
			[T_PRIVATE, T_PROTECTED, T_PUBLIC, T_VAR, T_STATIC],
			$propertyPointer - 1
		);

		$typeHintEndPointer = TokenHelper::findPrevious(
			$phpcsFile,
			TokenHelper::getTypeHintTokenCodes(),
			$propertyPointer - 1,
			$propertyStartPointer
		);
		if ($typeHintEndPointer === null) {
			return null;
		}

		$typeHintStartPointer = TokenHelper::findPreviousExcluding(
			$phpcsFile,
			TokenHelper::getTypeHintTokenCodes(),
			$typeHintEndPointer,
			$propertyStartPointer
		) + 1;

		$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $typeHintStartPointer - 1, $propertyStartPointer);
		$nullabilitySymbolPointer = $previousPointer !== null && $tokens[$previousPointer]['code'] === T_NULLABLE ? $previousPointer : null;

		return new PropertyTypeHint(
			TokenHelper::getContent($phpcsFile, $typeHintStartPointer, $typeHintEndPointer),
			$nullabilitySymbolPointer !== null,
			$nullabilitySymbolPointer ?? $typeHintStartPointer,
			$typeHintEndPointer
		);
	}

	public static function getFullyQualifiedName(File $phpcsFile, int $propertyPointer): string
	{
		$propertyToken = $phpcsFile->getTokens()[$propertyPointer];
		$propertyName = $propertyToken['content'];

		$classPointer = array_reverse(array_keys($propertyToken['conditions']))[0];
		if ($phpcsFile->getTokens()[$classPointer]['code'] === T_ANON_CLASS) {
			return sprintf('class@anonymous::%s', $propertyName);
		}

		$name = sprintf('%s%s::%s', NamespaceHelper::NAMESPACE_SEPARATOR, ClassHelper::getName($phpcsFile, $classPointer), $propertyName);
		$namespace = NamespaceHelper::findCurrentNamespaceName($phpcsFile, $propertyPointer);
		return $namespace !== null ? sprintf('%s%s%s', NamespaceHelper::NAMESPACE_SEPARATOR, $namespace, $name) : $name;
	}

}
