<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_key_exists;
use function sprintf;
use function strlen;
use const T_NAMESPACE;
use const T_NS_SEPARATOR;
use const T_SEMICOLON;
use const T_WHITESPACE;

class NamespaceDeclarationSniff implements Sniff
{

	public const CODE_INVALID_WHITESPACE_AFTER_NAMESPACE = 'InvalidWhitespaceAfterNamespace';
	public const CODE_DISALLOWED_CONTENT_BETWEEN_NAMESPACE_NAME_AND_SEMICOLON = 'DisallowedContentBetweenNamespaceNameAndSemicolon';
	public const CODE_DISALLOWED_BRACKETED_SYNTAX = 'DisallowedBracketedSyntax';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_NAMESPACE,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $namespacePointer
	 */
	public function process(File $phpcsFile, $namespacePointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$pointerAfterNamespace = TokenHelper::findNextEffective($phpcsFile, $namespacePointer + 1);
		if ($tokens[$pointerAfterNamespace]['code'] === T_NS_SEPARATOR) {
			return;
		}

		$this->checkWhitespaceAfterNamespace($phpcsFile, $namespacePointer);
		$this->checkDisallowedContentBetweenNamespaceNameAndSemicolon($phpcsFile, $namespacePointer);
		$this->checkDisallowedBracketedSyntax($phpcsFile, $namespacePointer);
	}

	private function checkWhitespaceAfterNamespace(File $phpcsFile, int $namespacePointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$whitespacePointer = $namespacePointer + 1;

		if ($tokens[$whitespacePointer]['code'] !== T_WHITESPACE) {
			$phpcsFile->addError(
				'Expected one space after namespace statement.',
				$namespacePointer,
				self::CODE_INVALID_WHITESPACE_AFTER_NAMESPACE
			);
			return;
		}

		if ($tokens[$whitespacePointer]['content'] === ' ') {
			return;
		}

		$errorMessage = $tokens[$whitespacePointer]['content'][0] === "\t"
			? 'Expected one space after namespace statement, found tab.'
			: sprintf('Expected one space after namespace statement, found %d.', strlen($tokens[$whitespacePointer]['content']));

		$fix = $phpcsFile->addFixableError($errorMessage, $namespacePointer, self::CODE_INVALID_WHITESPACE_AFTER_NAMESPACE);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($whitespacePointer, ' ');
		$phpcsFile->fixer->endChangeset();
	}

	private function checkDisallowedContentBetweenNamespaceNameAndSemicolon(File $phpcsFile, int $namespacePointer): void
	{
		if (array_key_exists('scope_opener', $phpcsFile->getTokens()[$namespacePointer])) {
			return;
		}

		$namespaceNameStartPointer = TokenHelper::findNextEffective($phpcsFile, $namespacePointer + 1);
		$namespaceNameEndPointer = TokenHelper::findNextExcluding(
			$phpcsFile,
			TokenHelper::getNameTokenCodes(),
			$namespaceNameStartPointer + 1
		) - 1;

		/** @var int $namespaceSemicolonPointer */
		$namespaceSemicolonPointer = TokenHelper::findNextLocal($phpcsFile, T_SEMICOLON, $namespaceNameEndPointer + 1);

		if ($namespaceNameEndPointer + 1 === $namespaceSemicolonPointer) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			'Disallowed content between namespace name and semicolon.',
			$namespacePointer,
			self::CODE_DISALLOWED_CONTENT_BETWEEN_NAMESPACE_NAME_AND_SEMICOLON
		);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		for ($i = $namespaceNameEndPointer + 1; $i < $namespaceSemicolonPointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}
		$phpcsFile->fixer->endChangeset();
	}

	private function checkDisallowedBracketedSyntax(File $phpcsFile, int $namespacePointer): void
	{
		$tokens = $phpcsFile->getTokens();

		if (!array_key_exists('scope_opener', $tokens[$namespacePointer])) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			'Bracketed syntax for namespaces is disallowed.',
			$namespacePointer,
			self::CODE_DISALLOWED_BRACKETED_SYNTAX
		);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($tokens[$namespacePointer]['scope_opener'], ';');
		$phpcsFile->fixer->replaceToken($tokens[$namespacePointer]['scope_closer'], '');
		$phpcsFile->fixer->endChangeset();
	}

}
