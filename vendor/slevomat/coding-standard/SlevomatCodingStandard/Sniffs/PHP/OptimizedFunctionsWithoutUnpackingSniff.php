<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\FunctionHelper;
use SlevomatCodingStandard\Helpers\NamespaceHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use SlevomatCodingStandard\Helpers\UseStatementHelper;
use function array_key_exists;
use function in_array;
use function sprintf;
use function substr;
use const T_COMMA;
use const T_ELLIPSIS;
use const T_FUNCTION;
use const T_NEW;
use const T_OBJECT_OPERATOR;
use const T_OPEN_PARENTHESIS;

class OptimizedFunctionsWithoutUnpackingSniff implements Sniff
{

	public const CODE_UNPACKING_USED = 'UnpackingUsed';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return TokenHelper::getOnlyNameTokenCodes();
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $pointer
	 */
	public function process(File $phpcsFile, $pointer): void
	{
		$previousTokenPointer = TokenHelper::findPreviousEffective($phpcsFile, $pointer - 1);
		$openBracketPointer = TokenHelper::findNextEffective($phpcsFile, $pointer + 1);
		$tokens = $phpcsFile->getTokens();

		if ($openBracketPointer === null || $tokens[$openBracketPointer]['code'] !== T_OPEN_PARENTHESIS) {
			return;
		}

		if (in_array($tokens[$previousTokenPointer]['code'], [T_FUNCTION, T_NEW, T_OBJECT_OPERATOR], true)) {
			return;
		}
		/** @var int $tokenBeforeInvocationPointer */
		$tokenBeforeInvocationPointer = TokenHelper::findPreviousExcluding($phpcsFile, TokenHelper::getNameTokenCodes(), $pointer);
		$invokedName = TokenHelper::getContent($phpcsFile, $tokenBeforeInvocationPointer + 1, $pointer);
		$useName = sprintf('function %s', $invokedName);

		$uses = UseStatementHelper::getUseStatementsForPointer($phpcsFile, $pointer);
		if ($invokedName[0] === '\\') {
			$invokedName = substr($invokedName, 1);
		} elseif (array_key_exists($useName, $uses) && $uses[$useName]->isFunction()) {
			$invokedName = $uses[$useName]->getFullyQualifiedTypeName();
		} elseif (NamespaceHelper::findCurrentNamespaceName($phpcsFile, $pointer) !== null) {
			return;
		}

		if (!in_array($invokedName, FunctionHelper::SPECIAL_FUNCTIONS, true)) {
			return;
		}

		$closeBracketPointer = $tokens[$openBracketPointer]['parenthesis_closer'];

		if (TokenHelper::findNextEffective($phpcsFile, $openBracketPointer + 1, $closeBracketPointer + 1) === $closeBracketPointer) {
			return;
		}

		$pointerBeforeCloseBracket = TokenHelper::findPreviousEffective($phpcsFile, $closeBracketPointer - 1);

		$startPointer = $tokens[$pointerBeforeCloseBracket]['code'] === T_COMMA ? $pointerBeforeCloseBracket : $closeBracketPointer;
		do {
			$lastArgumentSeparatorPointer = TokenHelper::findPrevious($phpcsFile, [T_COMMA], $startPointer - 1, $openBracketPointer);
			$startPointer = $lastArgumentSeparatorPointer;
		} while (
			$lastArgumentSeparatorPointer !== null
			&& $tokens[$lastArgumentSeparatorPointer]['level'] !== $tokens[$openBracketPointer]['level']
		);

		if ($lastArgumentSeparatorPointer === null) {
			$lastArgumentSeparatorPointer = $openBracketPointer;
		}

		/** @var int $nextTokenAfterSeparatorPointer */
		$nextTokenAfterSeparatorPointer = TokenHelper::findNextEffective(
			$phpcsFile,
			$lastArgumentSeparatorPointer + 1,
			$closeBracketPointer
		);

		if ($tokens[$nextTokenAfterSeparatorPointer]['code'] !== T_ELLIPSIS) {
			return;
		}

		$phpcsFile->addError(
			sprintf('Function %s is specialized by PHP and should not use argument unpacking.', $invokedName),
			$nextTokenAfterSeparatorPointer,
			self::CODE_UNPACKING_USED
		);
	}

}
