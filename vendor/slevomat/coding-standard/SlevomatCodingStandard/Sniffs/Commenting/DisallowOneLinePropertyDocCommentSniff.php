<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\DocCommentHelper;
use SlevomatCodingStandard\Helpers\PropertyHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function rtrim;
use function sprintf;
use const T_VARIABLE;
use const T_WHITESPACE;

class DisallowOneLinePropertyDocCommentSniff implements Sniff
{

	public const CODE_ONE_LINE_PROPERTY_COMMENT = 'OneLinePropertyComment';

	/**
	 * @return int[]
	 */
	public function register(): array
	{
		return [T_VARIABLE];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $propertyPointer
	 */
	public function process(File $phpcsFile, $propertyPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		// Not a property
		if (!PropertyHelper::isProperty($phpcsFile, $propertyPointer)) {
			return;
		}

		// Only validate properties with comment
		if (!DocCommentHelper::hasDocComment($phpcsFile, $propertyPointer)) {
			return;
		}

		/** @var int $docCommentStartPointer */
		$docCommentStartPointer = DocCommentHelper::findDocCommentOpenToken($phpcsFile, $propertyPointer);
		$docCommentEndPointer = $tokens[$docCommentStartPointer]['comment_closer'];
		$lineDifference = $tokens[$docCommentEndPointer]['line'] - $tokens[$docCommentStartPointer]['line'];

		// Already multi-line
		if ($lineDifference !== 0) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			sprintf(
				'Found one-line comment for property %s, use multi-line comment instead.',
				PropertyHelper::getFullyQualifiedName($phpcsFile, $propertyPointer)
			),
			$docCommentStartPointer,
			self::CODE_ONE_LINE_PROPERTY_COMMENT
		);

		if (!$fix) {
			return;
		}

		$commentWhitespacePointer = TokenHelper::findPrevious($phpcsFile, [T_WHITESPACE], $docCommentStartPointer);
		$indent = ($commentWhitespacePointer !== null ? $tokens[$commentWhitespacePointer]['content'] : '') . ' ';

		/** empty comment is not split into start & end tokens properly */
		if ($tokens[$docCommentStartPointer]['content'] === '/***/') {
			$phpcsFile->fixer->beginChangeset();

			$phpcsFile->fixer->replaceToken($docCommentStartPointer, '/**');
			$phpcsFile->fixer->addNewline($docCommentStartPointer);
			$phpcsFile->fixer->addContent($docCommentStartPointer, $indent);
			$phpcsFile->fixer->addContent($docCommentStartPointer, '*');
			$phpcsFile->fixer->addNewline($docCommentStartPointer);
			$phpcsFile->fixer->addContent($docCommentStartPointer, $indent);
			$phpcsFile->fixer->addContent($docCommentStartPointer, '*/');

			$phpcsFile->fixer->endChangeset();

			return;
		}

		$phpcsFile->fixer->beginChangeset();

		$phpcsFile->fixer->addNewline($docCommentStartPointer);
		$phpcsFile->fixer->addContent($docCommentStartPointer, $indent);
		$phpcsFile->fixer->addContent($docCommentStartPointer, '*');

		if ($docCommentEndPointer - 1 !== $docCommentStartPointer) {
			$phpcsFile->fixer->replaceToken(
				$docCommentEndPointer - 1,
				rtrim($phpcsFile->fixer->getTokenContent($docCommentEndPointer - 1), ' ')
			);
		}

		$phpcsFile->fixer->addContentBefore($docCommentEndPointer, $indent);
		$phpcsFile->fixer->addNewlineBefore($docCommentEndPointer);

		$phpcsFile->fixer->endChangeset();
	}

}
