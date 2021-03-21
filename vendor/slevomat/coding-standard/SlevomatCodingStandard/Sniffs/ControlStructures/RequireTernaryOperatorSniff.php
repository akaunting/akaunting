<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use SlevomatCodingStandard\Helpers\IdentificatorHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_key_exists;
use function sprintf;
use const T_BITWISE_AND;
use const T_ELSE;
use const T_EQUAL;
use const T_IF;
use const T_INLINE_THEN;
use const T_LOGICAL_AND;
use const T_LOGICAL_OR;
use const T_LOGICAL_XOR;
use const T_RETURN;
use const T_SEMICOLON;
use const T_WHITESPACE;

class RequireTernaryOperatorSniff implements Sniff
{

	public const CODE_TERNARY_OPERATOR_NOT_USED = 'TernaryOperatorNotUsed';

	/** @var bool */
	public $ignoreMultiLine = false;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_IF,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $ifPointer
	 */
	public function process(File $phpcsFile, $ifPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		if (!array_key_exists('scope_closer', $tokens[$ifPointer])) {
			// If without curly braces is not supported.
			return;
		}

		$elsePointer = TokenHelper::findNextEffective($phpcsFile, $tokens[$ifPointer]['scope_closer'] + 1);
		if ($elsePointer === null || $tokens[$elsePointer]['code'] !== T_ELSE) {
			return;
		}

		if (!array_key_exists('scope_closer', $tokens[$elsePointer])) {
			// Else without curly braces is not supported.
			return;
		}

		if (
			!$this->isCompatibleScope($phpcsFile, $tokens[$ifPointer]['scope_opener'], $tokens[$ifPointer]['scope_closer'])
			|| !$this->isCompatibleScope($phpcsFile, $tokens[$elsePointer]['scope_opener'], $tokens[$elsePointer]['scope_closer'])
		) {
			return;
		}

		/** @var int $firstPointerInIf */
		$firstPointerInIf = TokenHelper::findNextEffective($phpcsFile, $tokens[$ifPointer]['scope_opener'] + 1);
		/** @var int $firstPointerInElse */
		$firstPointerInElse = TokenHelper::findNextEffective($phpcsFile, $tokens[$elsePointer]['scope_opener'] + 1);

		if ($tokens[$firstPointerInIf]['code'] === T_RETURN && $tokens[$firstPointerInElse]['code'] === T_RETURN) {
			$this->checkIfWithReturns($phpcsFile, $ifPointer, $elsePointer, $firstPointerInIf, $firstPointerInElse);
			return;
		}

		$this->checkIfWithAssignments($phpcsFile, $ifPointer, $elsePointer, $firstPointerInIf, $firstPointerInElse);
	}

	private function checkIfWithReturns(File $phpcsFile, int $ifPointer, int $elsePointer, int $returnInIf, int $returnInElse): void
	{
		$ifContainsComment = $this->containsComment($phpcsFile, $ifPointer);
		$elseContainsComment = $this->containsComment($phpcsFile, $elsePointer);
		$conditionContainsLogicalOperators = $this->containsLogicalOperators($phpcsFile, $ifPointer);

		$errorParameters = [
			'Use ternary operator.',
			$ifPointer,
			self::CODE_TERNARY_OPERATOR_NOT_USED,
		];

		if ($ifContainsComment || $elseContainsComment || $conditionContainsLogicalOperators) {
			$phpcsFile->addError(...$errorParameters);
			return;
		}

		$fix = $phpcsFile->addFixableError(...$errorParameters);

		if (!$fix) {
			return;
		}

		$tokens = $phpcsFile->getTokens();

		$pointerAfterReturnInIf = TokenHelper::findNextEffective($phpcsFile, $returnInIf + 1);
		/** @var int $semicolonAfterReturnInIf */
		$semicolonAfterReturnInIf = TokenHelper::findNext($phpcsFile, T_SEMICOLON, $pointerAfterReturnInIf + 1);
		$pointerAfterReturnInElse = TokenHelper::findNextEffective($phpcsFile, $returnInElse + 1);
		$semicolonAfterReturnInElse = TokenHelper::findNext($phpcsFile, T_SEMICOLON, $pointerAfterReturnInElse + 1);

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($ifPointer, 'return');
		if ($ifPointer + 1 === $tokens[$ifPointer]['parenthesis_opener']) {
			$phpcsFile->fixer->addContent($ifPointer, ' ');
		}
		$phpcsFile->fixer->replaceToken($tokens[$ifPointer]['parenthesis_opener'], '');
		$phpcsFile->fixer->replaceToken($tokens[$ifPointer]['parenthesis_closer'], ' ? ');

		for ($i = $tokens[$ifPointer]['parenthesis_closer'] + 1; $i < $pointerAfterReturnInIf; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$phpcsFile->fixer->replaceToken($semicolonAfterReturnInIf, ' : ');

		for ($i = $semicolonAfterReturnInIf + 1; $i < $pointerAfterReturnInElse; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}
		for ($i = $semicolonAfterReturnInElse + 1; $i <= $tokens[$elsePointer]['scope_closer']; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$phpcsFile->fixer->endChangeset();
	}

	private function checkIfWithAssignments(
		File $phpcsFile,
		int $ifPointer,
		int $elsePointer,
		int $firstPointerInIf,
		int $firstPointerInElse
	): void
	{
		$tokens = $phpcsFile->getTokens();

		$identificatorEndPointerInIf = IdentificatorHelper::findEndPointer($phpcsFile, $firstPointerInIf);
		$identificatorEndPointerInElse = IdentificatorHelper::findEndPointer($phpcsFile, $firstPointerInElse);

		if ($identificatorEndPointerInIf === null || $identificatorEndPointerInElse === null) {
			return;
		}

		$identificatorInIf = TokenHelper::getContent($phpcsFile, $firstPointerInIf, $identificatorEndPointerInIf);
		$identificatorInElse = TokenHelper::getContent($phpcsFile, $firstPointerInElse, $identificatorEndPointerInElse);

		if ($identificatorInIf !== $identificatorInElse) {
			return;
		}

		$assignmentPointerInIf = TokenHelper::findNextEffective($phpcsFile, $identificatorEndPointerInIf + 1);
		$assignmentPointerInElse = TokenHelper::findNextEffective($phpcsFile, $identificatorEndPointerInElse + 1);

		if (
			$tokens[$assignmentPointerInIf]['code'] !== T_EQUAL
			|| $tokens[$assignmentPointerInElse]['code'] !== T_EQUAL
		) {
			return;
		}

		$pointerAfterAssignmentInIf = TokenHelper::findNextEffective($phpcsFile, $assignmentPointerInIf + 1);
		$pointerAfterAssignmentInElse = TokenHelper::findNextEffective($phpcsFile, $assignmentPointerInElse + 1);

		if (
			$tokens[$pointerAfterAssignmentInIf]['code'] === T_BITWISE_AND ||
			$tokens[$pointerAfterAssignmentInElse]['code'] === T_BITWISE_AND
		) {
			return;
		}

		$ifContainsComment = $this->containsComment($phpcsFile, $ifPointer);
		$elseContainsComment = $this->containsComment($phpcsFile, $elsePointer);
		$conditionContainsLogicalOperators = $this->containsLogicalOperators($phpcsFile, $ifPointer);

		$errorParameters = [
			'Use ternary operator.',
			$ifPointer,
			self::CODE_TERNARY_OPERATOR_NOT_USED,
		];

		if ($ifContainsComment || $elseContainsComment || $conditionContainsLogicalOperators) {
			$phpcsFile->addError(...$errorParameters);
			return;
		}

		$fix = $phpcsFile->addFixableError(...$errorParameters);

		if (!$fix) {
			return;
		}

		/** @var int $semicolonAfterAssignmentInIf */
		$semicolonAfterAssignmentInIf = TokenHelper::findNext($phpcsFile, T_SEMICOLON, $pointerAfterAssignmentInIf + 1);
		$semicolonAfterAssignmentInElse = TokenHelper::findNext($phpcsFile, T_SEMICOLON, $pointerAfterAssignmentInElse + 1);

		$phpcsFile->fixer->beginChangeset();

		$phpcsFile->fixer->replaceToken($ifPointer, sprintf('%s = ', $identificatorInIf));
		for ($i = $ifPointer + 1; $i <= $tokens[$ifPointer]['parenthesis_opener']; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}
		$phpcsFile->fixer->replaceToken($tokens[$ifPointer]['parenthesis_closer'], ' ? ');

		for ($i = $tokens[$ifPointer]['parenthesis_closer'] + 1; $i < $pointerAfterAssignmentInIf; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$phpcsFile->fixer->replaceToken($semicolonAfterAssignmentInIf, ' : ');

		for ($i = $semicolonAfterAssignmentInIf + 1; $i < $pointerAfterAssignmentInElse; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}
		for ($i = $semicolonAfterAssignmentInElse + 1; $i <= $tokens[$elsePointer]['scope_closer']; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$phpcsFile->fixer->endChangeset();
	}

	private function isCompatibleScope(File $phpcsFile, int $scopeOpenerPointer, int $scopeCloserPointer): bool
	{
		$semicolonPointer = TokenHelper::findNext($phpcsFile, T_SEMICOLON, $scopeOpenerPointer + 1, $scopeCloserPointer);
		if ($semicolonPointer === null) {
			return false;
		}

		if (TokenHelper::findNext($phpcsFile, T_INLINE_THEN, $scopeOpenerPointer + 1, $semicolonPointer) !== null) {
			return false;
		}

		if ($this->ignoreMultiLine) {
			$firstContentPointer = TokenHelper::findNextEffective($phpcsFile, $scopeOpenerPointer + 1);
			if (TokenHelper::findNextContent(
				$phpcsFile,
				T_WHITESPACE,
				$phpcsFile->eolChar,
				$firstContentPointer + 1,
				$semicolonPointer
			) !== null) {
				return false;
			}
		}

		$pointerAfterSemicolon = TokenHelper::findNextEffective($phpcsFile, $semicolonPointer + 1);
		return $pointerAfterSemicolon === $scopeCloserPointer;
	}

	private function containsComment(File $phpcsFile, int $scopeOwnerPointer): bool
	{
		$tokens = $phpcsFile->getTokens();
		return TokenHelper::findNext(
			$phpcsFile,
			Tokens::$commentTokens,
			$tokens[$scopeOwnerPointer]['scope_opener'] + 1,
			$tokens[$scopeOwnerPointer]['scope_closer']
		) !== null;
	}

	private function containsLogicalOperators(File $phpcsFile, int $scopeOwnerPointer): bool
	{
		$tokens = $phpcsFile->getTokens();
		return TokenHelper::findNext(
			$phpcsFile,
			[T_LOGICAL_AND, T_LOGICAL_OR, T_LOGICAL_XOR],
			$tokens[$scopeOwnerPointer]['parenthesis_opener'] + 1,
			$tokens[$scopeOwnerPointer]['parenthesis_closer']
		) !== null;
	}

}
