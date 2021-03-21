<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\FunctionHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function in_array;
use function ltrim;
use function sprintf;
use function strtolower;
use const T_CLASS_C;
use const T_DOUBLE_COLON;
use const T_NS_SEPARATOR;
use const T_OBJECT_OPERATOR;
use const T_OPEN_PARENTHESIS;
use const T_VARIABLE;

class ModernClassNameReferenceSniff implements Sniff
{

	public const CODE_CLASS_NAME_REFERENCED_VIA_MAGIC_CONSTANT = 'ClassNameReferencedViaMagicConstant';
	public const CODE_CLASS_NAME_REFERENCED_VIA_FUNCTION_CALL = 'ClassNameReferencedViaFunctionCall';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		$tokens = TokenHelper::getOnlyNameTokenCodes();
		$tokens[] = T_CLASS_C;

		return $tokens;
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $pointer
	 */
	public function process(File $phpcsFile, $pointer): void
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$pointer]['code'] === T_CLASS_C) {
			$this->checkMagicConstant($phpcsFile, $pointer);
			return;
		}

		$this->checkFunctionCall($phpcsFile, $pointer);
	}

	private function checkMagicConstant(File $phpcsFile, int $pointer): void
	{
		$fix = $phpcsFile->addFixableError(
			'Class name referenced via magic constant.',
			$pointer,
			self::CODE_CLASS_NAME_REFERENCED_VIA_MAGIC_CONSTANT
		);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($pointer, 'self::class');
		$phpcsFile->fixer->endChangeset();
	}

	private function checkFunctionCall(File $phpcsFile, int $functionPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$functionName = ltrim(strtolower($tokens[$functionPointer]['content']), '\\');

		$functionNames = [
			'get_class',
			'get_parent_class',
			'get_called_class',
		];

		if (!in_array($functionName, $functionNames, true)) {
			return;
		}

		$openParenthesisPointer = TokenHelper::findNextEffective($phpcsFile, $functionPointer + 1);
		if ($tokens[$openParenthesisPointer]['code'] !== T_OPEN_PARENTHESIS) {
			return;
		}

		$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $functionPointer - 1);
		if (in_array($tokens[$previousPointer]['code'], [T_OBJECT_OPERATOR, T_DOUBLE_COLON], true)) {
			return;
		}

		$parameterPointer = TokenHelper::findNextEffective(
			$phpcsFile,
			$openParenthesisPointer + 1,
			$tokens[$openParenthesisPointer]['parenthesis_closer']
		);

		$isThisParameter = static function () use ($phpcsFile, $tokens, $openParenthesisPointer, $parameterPointer): bool {
			if ($tokens[$parameterPointer]['code'] !== T_VARIABLE) {
				return false;
			}

			$parameterName = strtolower($tokens[$parameterPointer]['content']);
			if ($parameterName !== '$this') {
				return false;
			}

			$pointerAfterParameterPointer = TokenHelper::findNextEffective($phpcsFile, $parameterPointer + 1);
			return $pointerAfterParameterPointer === $tokens[$openParenthesisPointer]['parenthesis_closer'];
		};

		if ($functionName === 'get_class') {
			if ($parameterPointer === null) {
				$fixedContent = 'self::class';
			} elseif ($isThisParameter()) {
				$fixedContent = 'static::class';
			} else {
				return;
			}

		} elseif ($functionName === 'get_parent_class') {
			if ($parameterPointer !== null) {
				if (!$isThisParameter()) {
					return;
				}

				/** @var int $classPointer */
				$classPointer = FunctionHelper::findClassPointer($phpcsFile, $functionPointer);
				if (!ClassHelper::isFinal($phpcsFile, $classPointer)) {
					return;
				}
			}

			$fixedContent = 'parent::class';
		} else {
			$fixedContent = 'static::class';
		}

		$fix = $phpcsFile->addFixableError(
			sprintf('Class name referenced via call of function %s().', $functionName),
			$functionPointer,
			self::CODE_CLASS_NAME_REFERENCED_VIA_FUNCTION_CALL
		);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		if ($tokens[$functionPointer - 1]['code'] === T_NS_SEPARATOR) {
			$phpcsFile->fixer->replaceToken($functionPointer - 1, '');
		}
		$phpcsFile->fixer->replaceToken($functionPointer, $fixedContent);
		for ($i = $functionPointer + 1; $i <= $tokens[$openParenthesisPointer]['parenthesis_closer']; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}
		$phpcsFile->fixer->endChangeset();
	}

}
