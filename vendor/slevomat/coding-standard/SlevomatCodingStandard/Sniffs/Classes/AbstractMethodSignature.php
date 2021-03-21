<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\IndentationHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function assert;
use function is_string;
use function preg_replace;
use function rtrim;
use function sprintf;
use function str_replace;
use const T_FUNCTION;
use const T_OPEN_CURLY_BRACKET;
use const T_SEMICOLON;

/**
 * @internal
 */
abstract class AbstractMethodSignature implements Sniff
{

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [T_FUNCTION];
	}

	/**
	 * @param File $phpcsFile
	 * @param int $methodPointer
	 * @return array<int, int>
	 */
	protected function getSignatureStartAndEndPointers(File $phpcsFile, int $methodPointer): array
	{
		$signatureStartPointer = TokenHelper::findFirstTokenOnLine($phpcsFile, $methodPointer);

		/** @var int $pointerAfterSignatureEnd */
		$pointerAfterSignatureEnd = TokenHelper::findNext($phpcsFile, [T_OPEN_CURLY_BRACKET, T_SEMICOLON], $methodPointer + 1);
		if ($phpcsFile->getTokens()[$pointerAfterSignatureEnd]['code'] === T_SEMICOLON) {
			return [$signatureStartPointer, $pointerAfterSignatureEnd];
		}

		/** @var int $signatureEndPointer */
		$signatureEndPointer = TokenHelper::findPreviousEffective($phpcsFile, $pointerAfterSignatureEnd - 1);

		return [$signatureStartPointer, $signatureEndPointer];
	}

	protected function getSignature(File $phpcsFile, int $signatureStartPointer, int $signatureEndPointer): string
	{
		$signature = TokenHelper::getContent($phpcsFile, $signatureStartPointer, $signatureEndPointer);
		$signature = preg_replace(sprintf('~%s[ \t]*~', $phpcsFile->eolChar), ' ', $signature);
		assert(is_string($signature));

		$signature = str_replace(['( ', ' )'], ['(', ')'], $signature);
		$signature = rtrim($signature);

		return $signature;
	}

	protected function getSignatureWithoutTabs(File $phpcsFile, string $signature): string
	{
		return IndentationHelper::convertTabsToSpaces($phpcsFile, $signature);
	}

}
