<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function preg_match;
use function preg_replace;
use const T_END_HEREDOC;
use const T_HEREDOC;
use const T_START_HEREDOC;

class RequireNowdocSniff implements Sniff
{

	public const CODE_REQUIRED_NOWDOC = 'RequiredNowdoc';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_START_HEREDOC,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $heredocStartPointer
	 */
	public function process(File $phpcsFile, $heredocStartPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$heredocEndPointer = TokenHelper::findNext($phpcsFile, T_END_HEREDOC, $heredocStartPointer + 1);

		$heredocContentPointers = TokenHelper::findNextAll($phpcsFile, T_HEREDOC, $heredocStartPointer + 1, $heredocEndPointer);

		foreach ($heredocContentPointers as $heredocContentPointer) {
			if (preg_match('~(?<!\\\\)\$~', $tokens[$heredocContentPointer]['content']) > 0) {
				return;
			}
		}

		$fix = $phpcsFile->addFixableError('Use nowdoc syntax instead of heredoc.', $heredocStartPointer, self::CODE_REQUIRED_NOWDOC);
		if (!$fix) {
			return;
		}

		$nowdocStart = preg_replace('~^<<<"?(\w+)"?~', '<<<\'$1\'', $tokens[$heredocStartPointer]['content']);

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($heredocStartPointer, $nowdocStart);

		foreach ($heredocContentPointers as $heredocContentPointer) {
			$heredocContent = $tokens[$heredocContentPointer]['content'];
			$nowdocContent = preg_replace(
				'~\\\\(\\\\[nrtvef]|\$|\\\\|\\\\[0-7]{1,3}|\\\\x[0-9A-Fa-f]{1,2}|\\\\u\{[0-9A-Fa-f]+\})~',
				'$1',
				$heredocContent
			);

			$phpcsFile->fixer->replaceToken($heredocContentPointer, $nowdocContent);
		}

		$phpcsFile->fixer->endChangeset();
	}

}
