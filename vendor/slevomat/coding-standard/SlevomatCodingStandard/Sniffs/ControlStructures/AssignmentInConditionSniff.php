<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_keys;
use function max;
use function sprintf;
use const T_DO;
use const T_ELSEIF;
use const T_EQUAL;
use const T_IF;
use const T_WHILE;

class AssignmentInConditionSniff implements Sniff
{

	public const CODE_ASSIGNMENT_IN_CONDITION = 'AssignmentInCondition';

	/** @var bool */
	public $ignoreAssignmentsInsideFunctionCalls = false;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_IF,
			T_ELSEIF,
			T_DO,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $conditionStartPointer
	 */
	public function process(File $phpcsFile, $conditionStartPointer): void
	{
		$tokens = $phpcsFile->getTokens();
		$token = $tokens[$conditionStartPointer];
		if ($token['code'] === T_DO) {
			$whilePointer = TokenHelper::findNext($phpcsFile, T_WHILE, $token['scope_closer'] + 1);
			$whileToken = $tokens[$whilePointer];
			$parenthesisOpener = $whileToken['parenthesis_opener'];
			$parenthesisCloser = $whileToken['parenthesis_closer'];
			$type = 'do-while';
		} else {
			$parenthesisOpener = $token['parenthesis_opener'];
			$parenthesisCloser = $token['parenthesis_closer'];
			$type = $token['code'] === T_IF ? 'if' : 'elseif';
		}
		$this->processCondition($phpcsFile, $parenthesisOpener, $parenthesisCloser, $type);
	}

	private function processCondition(File $phpcsFile, int $parenthesisOpener, int $parenthesisCloser, string $conditionType): void
	{
		$equalsTokenPointers = TokenHelper::findNextAll($phpcsFile, T_EQUAL, $parenthesisOpener + 1, $parenthesisCloser);
		$tokens = $phpcsFile->getTokens();
		if ($equalsTokenPointers === []) {
			return;
		}

		if (!$this->ignoreAssignmentsInsideFunctionCalls) {
			$this->error($phpcsFile, $conditionType, $equalsTokenPointers[0]);
			return;
		}

		foreach ($equalsTokenPointers as $equalsTokenPointer) {
			$parenthesisStarts = array_keys($tokens[$equalsTokenPointer]['nested_parenthesis']);

			/** @var int $insideParenthesis */
			$insideParenthesis = max($parenthesisStarts);
			if ($insideParenthesis === $parenthesisOpener) {
				$this->error($phpcsFile, $conditionType, $equalsTokenPointer);
				continue;
			}

			$functionCall = TokenHelper::findPrevious(
				$phpcsFile,
				TokenHelper::getOnlyNameTokenCodes(),
				$insideParenthesis,
				$parenthesisOpener
			);
			if ($functionCall !== null) {
				continue;
			}

			$this->error($phpcsFile, $conditionType, $equalsTokenPointer);
		}
	}

	private function error(File $phpcsFile, string $conditionType, int $equalsTokenPointer): void
	{
		$phpcsFile->addError(
			sprintf('Assignment in %s condition is not allowed.', $conditionType),
			$equalsTokenPointer,
			self::CODE_ASSIGNMENT_IN_CONDITION
		);
	}

}
